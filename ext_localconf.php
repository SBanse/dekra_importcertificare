<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function () {
    // Plugin: Zertifikatsanfrage (mehrstufiges Formular)
    ExtensionUtility::configurePlugin(
        'DekraImportcertificate',
        'CertificateRequest',
        [
            \Vendor\DekraImportcertificate\Controller\CertificateController::class => 'index, step1, step2, step3, submit, confirmation, status',
        ],
        // Non-cacheable actions
        [
            \Vendor\DekraImportcertificate\Controller\CertificateController::class => 'index, step1, step2, step3, submit, confirmation, status',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Plugin: Fahrzeugsuche / Preisanfrage
    ExtensionUtility::configurePlugin(
        'DekraImportcertificate',
        'VehicleSearch',
        [
            \Vendor\DekraImportcertificate\Controller\VehicleSearchController::class => 'index, search, result',
        ],
        [
            \Vendor\DekraImportcertificate\Controller\VehicleSearchController::class => 'index, search, result',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Plugin: Beispielzertifikate anzeigen
    ExtensionUtility::configurePlugin(
        'DekraImportcertificate',
        'SampleCertificates',
        [
            \Vendor\DekraImportcertificate\Controller\SampleCertificateController::class => 'index, show',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Plugin: FAQ
    ExtensionUtility::configurePlugin(
        'DekraImportcertificate',
        'Faq',
        [
            \Vendor\DekraImportcertificate\Controller\FaqController::class => 'index',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
})();
