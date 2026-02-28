<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use Vendor\DekraImportcertificate\Domain\Model\CertificateRequest;
use Vendor\DekraImportcertificate\Domain\Repository\CertificateRequestRepository;
use Vendor\DekraImportcertificate\Service\DekraApiService;
use Vendor\DekraImportcertificate\Service\EmailService;

/**
 * Controller für den mehrstufigen Zertifikatsanfrage-Prozess
 *
 * Schritt 1: Fahrzeugdaten / VIN-Lookup
 * Schritt 2: Zertifikatstyp & Kundendaten
 * Schritt 3: Zusammenfassung & Abschicken
 */
class CertificateController extends ActionController
{
    public function __construct(
        private readonly DekraApiService $dekraApiService,
        private readonly EmailService $emailService,
        private readonly CertificateRequestRepository $certificateRequestRepository,
        private readonly PersistenceManagerInterface $persistenceManager
    ) {}

    /**
     * Startseite / Einstieg
     */
    public function indexAction(): ResponseInterface
    {
        $this->view->assign('settings', $this->settings);
        return $this->htmlResponse();
    }

    /**
     * Schritt 1: Fahrzeugdaten eingeben
     */
    public function step1Action(CertificateRequest $request = null): ResponseInterface
    {
        if ($request === null) {
            $request = GeneralUtility::makeInstance(CertificateRequest::class);
        }

        $this->view->assignMultiple([
            'request' => $request,
            'importCountries' => $this->dekraApiService->getImportCountries(),
            'vehicleCategories' => $this->getVehicleCategories(),
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Schritt 2: Zertifikatstyp & Kundendaten
     */
    public function step2Action(CertificateRequest $request): ResponseInterface
    {
        // VIN-Lookup durchführen
        $vinData = [];
        if (!empty($request->getVin())) {
            $vinData = $this->dekraApiService->lookupVehicleByVin($request->getVin());
        }

        $this->view->assignMultiple([
            'request' => $request,
            'vinData' => $vinData,
            'certificateTypes' => $this->dekraApiService->getCertificateTypes(
                $request->getImportCountry(),
                $request->getTargetCountry()
            ),
            'settings' => $this->settings,
        ]);

        // Request in Session speichern
        $this->storeRequestInSession($request);

        return $this->htmlResponse();
    }

    /**
     * Schritt 3: Zusammenfassung & Bestätigung
     */
    public function step3Action(CertificateRequest $request): ResponseInterface
    {
        // Session-Daten wiederherstellen und mergen
        $sessionRequest = $this->getRequestFromSession();
        if ($sessionRequest !== null) {
            $this->mergeRequestData($sessionRequest, $request);
            $request = $sessionRequest;
        }

        $this->view->assignMultiple([
            'request' => $request,
            'certificateTypes' => $this->dekraApiService->getCertificateTypes(),
            'importCountries' => $this->dekraApiService->getImportCountries(),
            'settings' => $this->settings,
        ]);

        $this->storeRequestInSession($request);

        return $this->htmlResponse();
    }

    /**
     * Formular absenden
     */
    public function submitAction(CertificateRequest $request): ResponseInterface
    {
        // Session-Daten holen und mergen
        $sessionRequest = $this->getRequestFromSession();
        if ($sessionRequest !== null) {
            $this->mergeRequestData($sessionRequest, $request);
            $request = $sessionRequest;
        }

        // Referenznummer generieren
        $request->generateReferenceNumber();

        // In Datenbank speichern
        $this->certificateRequestRepository->add($request);
        $this->persistenceManager->persistAll();

        // An DEKRA-Portal senden
        try {
            $apiResponse = $this->dekraApiService->submitCertificateRequest($request);
            if (!empty($apiResponse['success'])) {
                $request->setStatus('pending');
            }
        } catch (\Exception $e) {
            $request->setStatus('pending'); // Trotzdem gespeichert, manuelle Nachbearbeitung
        }

        // E-Mails versenden
        $this->emailService->sendConfirmationToCustomer($request);
        $this->emailService->sendNotificationToAdmin($request);

        // Session leeren
        $this->clearRequestSession();

        // Weiterleitung zur Bestätigungsseite
        return $this->redirect('confirmation', null, null, [
            'referenceNumber' => $request->getReferenceNumber(),
        ]);
    }

    /**
     * Bestätigungsseite
     */
    public function confirmationAction(string $referenceNumber = ''): ResponseInterface
    {
        $request = null;
        if (!empty($referenceNumber)) {
            $request = $this->certificateRequestRepository->findByReferenceNumber($referenceNumber);
        }

        $this->view->assignMultiple([
            'request' => $request,
            'referenceNumber' => $referenceNumber,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Status einer Anfrage abfragen
     */
    public function statusAction(string $referenceNumber = ''): ResponseInterface
    {
        $statusData = [];
        $request = null;

        if (!empty($referenceNumber)) {
            $request = $this->certificateRequestRepository->findByReferenceNumber($referenceNumber);
            if ($request) {
                $statusData = $this->dekraApiService->getRequestStatus($referenceNumber);
            }
        }

        $this->view->assignMultiple([
            'request' => $request,
            'statusData' => $statusData,
            'referenceNumber' => $referenceNumber,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }

    // -------------------------------------------------------------------------
    // Hilfsmethoden
    // -------------------------------------------------------------------------

    private function getVehicleCategories(): array
    {
        return [
            'PKW' => 'PKW (Personenkraftwagen)',
            'LKW' => 'LKW (Lastkraftwagen)',
            'MOTORRAD' => 'Motorrad',
            'WOHNMOBIL' => 'Wohnmobil / Reisemobil',
            'ANHAENGER' => 'Anhänger',
            'OLDTIMER' => 'Oldtimer',
            'SONDER' => 'Sonderfahrzeug',
        ];
    }

    private function storeRequestInSession(CertificateRequest $request): void
    {
        $sessionData = [
            'vin' => $request->getVin(),
            'vehicleMake' => $request->getVehicleMake(),
            'vehicleModel' => $request->getVehicleModel(),
            'vehicleYear' => $request->getVehicleYear(),
            'vehicleCategory' => $request->getVehicleCategory(),
            'importCountry' => $request->getImportCountry(),
            'targetCountry' => $request->getTargetCountry(),
            'certificateType' => $request->getCertificateType(),
            'salutation' => $request->getSalutation(),
            'firstName' => $request->getFirstName(),
            'lastName' => $request->getLastName(),
            'company' => $request->getCompany(),
            'street' => $request->getStreet(),
            'zip' => $request->getZip(),
            'city' => $request->getCity(),
            'country' => $request->getCountry(),
            'phone' => $request->getPhone(),
            'email' => $request->getEmail(),
            'additionalInfo' => $request->getAdditionalInfo(),
            'urgentProcessing' => $request->isUrgentProcessing(),
        ];

        $GLOBALS['TSFE']->fe_user->setKey('ses', 'dekra_certificate_request', $sessionData);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

    private function getRequestFromSession(): ?CertificateRequest
    {
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'dekra_certificate_request');
        if (empty($sessionData)) {
            return null;
        }

        $request = GeneralUtility::makeInstance(CertificateRequest::class);
        foreach ($sessionData as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($request, $setter)) {
                $request->$setter($value);
            }
        }

        return $request;
    }

    private function mergeRequestData(CertificateRequest $target, CertificateRequest $source): void
    {
        // Nicht-leere Felder aus Source in Target übernehmen
        if (!empty($source->getVin())) $target->setVin($source->getVin());
        if (!empty($source->getCertificateType())) $target->setCertificateType($source->getCertificateType());
        if (!empty($source->getSalutation())) $target->setSalutation($source->getSalutation());
        if (!empty($source->getFirstName())) $target->setFirstName($source->getFirstName());
        if (!empty($source->getLastName())) $target->setLastName($source->getLastName());
        if (!empty($source->getEmail())) $target->setEmail($source->getEmail());
        if (!empty($source->getPhone())) $target->setPhone($source->getPhone());
        if (!empty($source->getStreet())) $target->setStreet($source->getStreet());
        if (!empty($source->getZip())) $target->setZip($source->getZip());
        if (!empty($source->getCity())) $target->setCity($source->getCity());
        $target->setPrivacyAccepted($source->isPrivacyAccepted());
        $target->setTermsAccepted($source->isTermsAccepted());
        $target->setUrgentProcessing($source->isUrgentProcessing());
        if (!empty($source->getAdditionalInfo())) $target->setAdditionalInfo($source->getAdditionalInfo());
    }

    private function clearRequestSession(): void
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'dekra_certificate_request', null);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }
}
