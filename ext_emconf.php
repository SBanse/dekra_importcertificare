<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'DEKRA Importcertificate24',
    'description' => 'TYPO3 Extension zur Integration des DEKRA Importcertificate24 Portals. Ermöglicht Zertifikatsanfragen für Importfahrzeuge direkt auf Ihrer TYPO3-Website.',
    'category' => 'plugin',
    'author' => 'Ihr Name',
    'author_email' => 'info@example.com',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.0.0-13.9.99',
            'extbase' => '13.0.0-13.9.99',
            'fluid' => '13.0.0-13.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
