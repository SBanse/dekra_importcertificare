<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller für Beispielzertifikate
 */
class SampleCertificateController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        $samples = [
            [
                'country' => 'Deutschland',
                'code' => 'DE',
                'types' => ['§ 21 StVZO Vollgutachten', '§ 13 FZV Einzelgenehmigung'],
                'description' => 'Für die Zulassung von Importfahrzeugen in Deutschland.',
                'flag' => '🇩🇪',
            ],
            [
                'country' => 'Österreich',
                'code' => 'AT',
                'types' => ['Einzelgenehmigung', 'Technisches Datenblatt'],
                'description' => 'Dokumente für die österreichische Zulassung.',
                'flag' => '🇦🇹',
            ],
            [
                'country' => 'Schweiz',
                'code' => 'CH',
                'types' => ['Fahrzeugzertifikat', 'Abgasgutachten'],
                'description' => 'Schweizer Zulassungsdokumente für Importfahrzeuge.',
                'flag' => '🇨🇭',
            ],
            [
                'country' => 'Frankreich',
                'code' => 'FR',
                'types' => ['Certificat de conformité', 'Réception à titre isolé'],
                'description' => 'Dokumente für die französische Zulassung.',
                'flag' => '🇫🇷',
            ],
            [
                'country' => 'USA',
                'code' => 'US',
                'types' => ['EPA Certificate', 'DOT Compliance'],
                'description' => 'US-amerikanische Fahrzeugzertifikate.',
                'flag' => '🇺🇸',
            ],
        ];

        $this->view->assignMultiple([
            'samples' => $samples,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }
}
