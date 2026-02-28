<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

// Plugin: Zertifikatsanfrage
ExtensionUtility::registerPlugin(
    'DekraImportcertificate',
    'CertificateRequest',
    'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang.xlf:plugin.certificateRequest.title',
    'EXT:dekra_importcertificate/Resources/Public/Icons/certificate.svg',
    'forms',
    'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang.xlf:plugin.certificateRequest.description'
);

// Plugin: Fahrzeugsuche
ExtensionUtility::registerPlugin(
    'DekraImportcertificate',
    'VehicleSearch',
    'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang.xlf:plugin.vehicleSearch.title',
    'EXT:dekra_importcertificate/Resources/Public/Icons/search.svg',
    'special'
);

// Plugin: Beispielzertifikate
ExtensionUtility::registerPlugin(
    'DekraImportcertificate',
    'SampleCertificates',
    'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang.xlf:plugin.sampleCertificates.title',
    'EXT:dekra_importcertificate/Resources/Public/Icons/certificate.svg',
    'special'
);

// Plugin: FAQ
ExtensionUtility::registerPlugin(
    'DekraImportcertificate',
    'Faq',
    'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang.xlf:plugin.faq.title',
    null,
    'special'
);

// TypoScript einbinden
ExtensionManagementUtility::addStaticFile(
    'dekra_importcertificate',
    'Configuration/TypoScript/',
    'DEKRA Importcertificate24'
);
