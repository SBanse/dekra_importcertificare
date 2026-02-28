<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:dekra_importcertificate/Resources/Private/Language/locallang_db.xlf:tx_dekraimportcertificate.certificaterequest',
        'label' => 'reference_number',
        'label_alt' => 'last_name, vin',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => ['disabled' => 'hidden'],
        'iconfile' => 'EXT:dekra_importcertificate/Resources/Public/Icons/certificate.svg',
        'searchFields' => 'vin, last_name, first_name, email, reference_number',
        'sortby' => 'crdate',
    ],

    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => ['type' => 'check', 'renderType' => 'checkboxToggle', 'items' => [['label' => '', 'invertStateDisplay' => true]]],
        ],

        'vin' => [
            'label' => 'FIN / VIN',
            'config' => ['type' => 'input', 'size' => 17, 'max' => 17, 'eval' => 'trim,upper', 'required' => true],
        ],
        'vehicle_make' => [
            'label' => 'Hersteller',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'vehicle_model' => [
            'label' => 'Modell',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'vehicle_year' => [
            'label' => 'Baujahr',
            'config' => ['type' => 'number', 'range' => ['lower' => 1960, 'upper' => 2030]],
        ],
        'vehicle_category' => [
            'label' => 'Fahrzeugkategorie',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'PKW', 'value' => 'PKW'],
                    ['label' => 'LKW', 'value' => 'LKW'],
                    ['label' => 'Motorrad', 'value' => 'MOTORRAD'],
                    ['label' => 'Wohnmobil', 'value' => 'WOHNMOBIL'],
                    ['label' => 'Oldtimer', 'value' => 'OLDTIMER'],
                    ['label' => 'Sonderfahrzeug', 'value' => 'SONDER'],
                ],
            ],
        ],
        'import_country' => [
            'label' => 'Herkunftsland',
            'config' => ['type' => 'input', 'size' => 3, 'max' => 3],
        ],
        'certificate_type' => [
            'label' => 'Zertifikatstyp',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'salutation' => [
            'label' => 'Anrede',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Herr', 'value' => 'Herr'],
                    ['label' => 'Frau', 'value' => 'Frau'],
                    ['label' => 'Divers', 'value' => 'Divers'],
                ],
            ],
        ],
        'first_name' => [
            'label' => 'Vorname',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'last_name' => [
            'label' => 'Nachname',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim', 'required' => true],
        ],
        'company' => [
            'label' => 'Firma',
            'config' => ['type' => 'input', 'size' => 40, 'eval' => 'trim'],
        ],
        'street' => [
            'label' => 'Straße',
            'config' => ['type' => 'input', 'size' => 40, 'eval' => 'trim'],
        ],
        'zip' => [
            'label' => 'PLZ',
            'config' => ['type' => 'input', 'size' => 10, 'eval' => 'trim'],
        ],
        'city' => [
            'label' => 'Ort',
            'config' => ['type' => 'input', 'size' => 30, 'eval' => 'trim'],
        ],
        'email' => [
            'label' => 'E-Mail',
            'config' => ['type' => 'input', 'size' => 40, 'eval' => 'trim,lower'],
        ],
        'phone' => [
            'label' => 'Telefon',
            'config' => ['type' => 'input', 'size' => 20, 'eval' => 'trim'],
        ],
        'additional_info' => [
            'label' => 'Zusätzliche Informationen',
            'config' => ['type' => 'text', 'rows' => 5, 'cols' => 60],
        ],
        'urgent_processing' => [
            'label' => 'Expressbearbeitung',
            'config' => ['type' => 'check', 'renderType' => 'checkboxToggle'],
        ],
        'status' => [
            'label' => 'Status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => 'Neu', 'value' => 'new'],
                    ['label' => 'Ausstehend', 'value' => 'pending'],
                    ['label' => 'In Bearbeitung', 'value' => 'processing'],
                    ['label' => 'Abgeschlossen', 'value' => 'completed'],
                    ['label' => 'Abgelehnt', 'value' => 'rejected'],
                ],
            ],
        ],
        'reference_number' => [
            'label' => 'Referenznummer',
            'config' => ['type' => 'input', 'size' => 20, 'readOnly' => true],
        ],
        'request_date' => [
            'label' => 'Anfragedatum',
            'config' => ['type' => 'datetime'],
        ],
    ],

    'types' => [
        '1' => [
            'showitem' => '
                --palette--;;status_palette,
                --div--;Fahrzeugdaten,
                    vin, vehicle_make, vehicle_model,
                    --palette--;;vehicle_details,
                    import_country, certificate_type, urgent_processing,
                --div--;Kundendaten,
                    salutation, first_name, last_name, company,
                    street, zip, city, email, phone,
                    additional_info,
            ',
        ],
    ],

    'palettes' => [
        'status_palette' => ['showitem' => 'reference_number, status, request_date'],
        'vehicle_details' => ['showitem' => 'vehicle_year, vehicle_category'],
    ],
];
