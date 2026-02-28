<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vendor\DekraImportcertificate\Domain\Model\CertificateRequest;

/**
 * E-Mail-Service für Bestätigungen und Benachrichtigungen
 */
class EmailService
{
    private array $config;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {
        $this->config = $extensionConfiguration->get('dekra_importcertificate');
    }

    /**
     * Sendet Bestätigungs-E-Mail an den Kunden
     */
    public function sendConfirmationToCustomer(CertificateRequest $request): bool
    {
        try {
            $email = GeneralUtility::makeInstance(FluidEmail::class);
            $email
                ->to(new Address($request->getEmail(), $request->getFullName()))
                ->from(new Address(
                    $this->config['senderEmail'] ?? 'info@example.com',
                    $this->config['senderName'] ?? 'DEKRA Importcertificate'
                ))
                ->subject('Ihre Zertifikatsanfrage wurde eingereicht – ' . $request->getReferenceNumber())
                ->setTemplate('Email/CustomerConfirmation')
                ->assignMultiple([
                    'request' => $request,
                    'referenceNumber' => $request->getReferenceNumber(),
                    'siteUrl' => $GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getBase()->__toString(),
                ]);

            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Bestätigungs-E-Mail konnte nicht gesendet werden', [
                'email' => $request->getEmail(),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Sendet Benachrichtigung an den Administrator
     */
    public function sendNotificationToAdmin(CertificateRequest $request): bool
    {
        $adminEmail = $this->config['adminEmail'] ?? '';
        if (empty($adminEmail)) {
            return false;
        }

        try {
            $email = GeneralUtility::makeInstance(FluidEmail::class);
            $email
                ->to(new Address($adminEmail))
                ->from(new Address(
                    $this->config['senderEmail'] ?? 'info@example.com',
                    $this->config['senderName'] ?? 'DEKRA Importcertificate'
                ))
                ->subject('[Neue Anfrage] ' . $request->getReferenceNumber() . ' – ' . $request->getVehicleMake() . ' ' . $request->getVehicleModel())
                ->setTemplate('Email/AdminNotification')
                ->assignMultiple([
                    'request' => $request,
                ]);

            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Admin-Benachrichtigung fehlgeschlagen', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
