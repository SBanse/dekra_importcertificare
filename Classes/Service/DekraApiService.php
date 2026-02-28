<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Service;

use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use Vendor\DekraImportcertificate\Domain\Model\CertificateRequest;

/**
 * Service für die Kommunikation mit dekra-importcertificate24.de
 *
 * Kapselt alle HTTP-Anfragen an das DEKRA-Portal.
 * Die Authentifizierung und Endpunkte müssen ggf. an die
 * tatsächliche API-Dokumentation angepasst werden.
 */
class DekraApiService
{
    private const BASE_URL = 'https://dekra-importcertificate24.de';
    private const TIMEOUT = 30;

    private string $apiKey;
    private string $partnerId;
    private bool $sandboxMode;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly LoggerInterface $logger,
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {
        $config = $extensionConfiguration->get('dekra_importcertificate');
        $this->apiKey = (string)($config['apiKey'] ?? '');
        $this->partnerId = (string)($config['partnerId'] ?? '');
        $this->sandboxMode = (bool)($config['sandboxMode'] ?? true);
    }

    /**
     * Ruft Fahrzeugdaten anhand der FIN/VIN ab
     */
    public function lookupVehicleByVin(string $vin): array
    {
        if (empty($this->apiKey)) {
            return $this->getMockVehicleData($vin);
        }

        try {
            $url = $this->buildUrl('/api/v1/vehicle/lookup');
            $response = $this->requestFactory->request($url, 'POST', [
                'headers' => $this->getHeaders(),
                'json' => ['vin' => $vin],
                'timeout' => self::TIMEOUT,
            ]);

            $data = json_decode((string)$response->getBody(), true);
            return $data ?? [];
        } catch (\Exception $e) {
            $this->logger->error('DEKRA API VIN-Lookup fehlgeschlagen', [
                'vin' => $vin,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Sendet eine Zertifikatsanfrage an das DEKRA-Portal
     */
    public function submitCertificateRequest(CertificateRequest $request): array
    {
        if (empty($this->apiKey) || $this->sandboxMode) {
            return $this->getMockSubmitResponse($request);
        }

        try {
            $url = $this->buildUrl('/api/v1/certificate/request');
            $payload = $this->buildRequestPayload($request);

            $response = $this->requestFactory->request($url, 'POST', [
                'headers' => $this->getHeaders(),
                'json' => $payload,
                'timeout' => self::TIMEOUT,
            ]);

            $data = json_decode((string)$response->getBody(), true);

            $this->logger->info('DEKRA Zertifikatsanfrage eingereicht', [
                'referenceNumber' => $request->getReferenceNumber(),
                'response' => $data,
            ]);

            return $data ?? [];
        } catch (\Exception $e) {
            $this->logger->error('DEKRA API Einreichung fehlgeschlagen', [
                'referenceNumber' => $request->getReferenceNumber(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Ruft den Status einer Anfrage ab
     */
    public function getRequestStatus(string $referenceNumber): array
    {
        if (empty($this->apiKey) || $this->sandboxMode) {
            return [
                'referenceNumber' => $referenceNumber,
                'status' => 'processing',
                'statusLabel' => 'In Bearbeitung',
                'estimatedDelivery' => date('Y-m-d', strtotime('+3 business days')),
                'message' => 'Ihre Anfrage wird aktuell bearbeitet.',
            ];
        }

        try {
            $url = $this->buildUrl('/api/v1/certificate/status/' . urlencode($referenceNumber));
            $response = $this->requestFactory->request($url, 'GET', [
                'headers' => $this->getHeaders(),
                'timeout' => self::TIMEOUT,
            ]);

            return json_decode((string)$response->getBody(), true) ?? [];
        } catch (\Exception $e) {
            $this->logger->error('DEKRA Status-Abruf fehlgeschlagen', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Gibt verfügbare Zertifikatstypen zurück
     */
    public function getCertificateTypes(string $importCountry = '', string $targetCountry = 'DE'): array
    {
        // Bekannte Zertifikatstypen basierend auf der Website
        return [
            'vollgutachten' => 'Vollgutachten (§ 21 StVZO)',
            'einzelgenehmigung' => 'Einzelgenehmigung (§ 13 FZV)',
            'abgasgutachten' => 'Abgasgutachten',
            'datenblaetter' => 'Technisches Datenblatt / Zertifikat der Rechtskonformität',
            'geraeuschgutachten' => 'Geräuschgutachten',
            'bremsgutachten' => 'Bremsgutachten',
            'lichtgutachten' => 'Lichtgutachten (Scheinwerfernachweis)',
            'emv' => 'EMV-Nachweis (elektromagnetische Verträglichkeit)',
        ];
    }

    /**
     * Gibt unterstützte Herkunftsländer zurück
     */
    public function getImportCountries(): array
    {
        return [
            'US' => 'USA',
            'JP' => 'Japan',
            'GB' => 'Großbritannien',
            'AU' => 'Australien',
            'CA' => 'Kanada',
            'KR' => 'Südkorea',
            'AE' => 'Vereinigte Arabische Emirate',
            'ZA' => 'Südafrika',
            'MX' => 'Mexiko',
            'BR' => 'Brasilien',
            'OTHER' => 'Sonstiges Drittland',
        ];
    }

    /**
     * Erstellt die Anfrage-Payload für die API
     */
    private function buildRequestPayload(CertificateRequest $request): array
    {
        return [
            'partnerId' => $this->partnerId,
            'referenceNumber' => $request->getReferenceNumber(),
            'vehicle' => [
                'vin' => $request->getVin(),
                'make' => $request->getVehicleMake(),
                'model' => $request->getVehicleModel(),
                'year' => $request->getVehicleYear(),
                'category' => $request->getVehicleCategory(),
                'importCountry' => $request->getImportCountry(),
                'targetCountry' => $request->getTargetCountry(),
            ],
            'certificateType' => $request->getCertificateType(),
            'customer' => [
                'salutation' => $request->getSalutation(),
                'firstName' => $request->getFirstName(),
                'lastName' => $request->getLastName(),
                'company' => $request->getCompany(),
                'address' => [
                    'street' => $request->getStreet(),
                    'zip' => $request->getZip(),
                    'city' => $request->getCity(),
                    'country' => $request->getCountry(),
                ],
                'phone' => $request->getPhone(),
                'email' => $request->getEmail(),
            ],
            'options' => [
                'urgent' => $request->isUrgentProcessing(),
                'additionalInfo' => $request->getAdditionalInfo(),
            ],
        ];
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Partner-ID' => $this->partnerId,
        ];
    }

    private function buildUrl(string $path): string
    {
        return self::BASE_URL . $path;
    }

    /**
     * Mock-Daten für Entwicklung/Demo ohne API-Key
     */
    private function getMockVehicleData(string $vin): array
    {
        return [
            'vin' => $vin,
            'make' => 'Beispiel-Hersteller',
            'model' => 'Beispiel-Modell',
            'year' => 2020,
            'category' => 'PKW',
            'found' => true,
            'dataAvailable' => true,
            'estimatedPrice' => '350-450 EUR',
            'deliveryDays' => '3-7 Werktage',
        ];
    }

    private function getMockSubmitResponse(CertificateRequest $request): array
    {
        return [
            'success' => true,
            'referenceNumber' => $request->getReferenceNumber(),
            'message' => 'Ihre Anfrage wurde erfolgreich eingereicht. Sie erhalten eine Bestätigung per E-Mail.',
            'estimatedDelivery' => date('Y-m-d', strtotime('+5 business days')),
            'sandboxMode' => true,
        ];
    }
}
