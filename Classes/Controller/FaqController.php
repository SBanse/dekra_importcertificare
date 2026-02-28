<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller für FAQ-Seite
 */
class FaqController extends ActionController
{
    public function indexAction(): ResponseInterface
    {
        $faqs = [
            [
                'category' => 'Allgemein',
                'questions' => [
                    [
                        'question' => 'Was ist ein Importzertifikat?',
                        'answer' => 'Ein Importzertifikat (auch technisches Datenblatt oder Zertifikat der Rechtskonformität genannt) ist ein Dokument, das für die Zulassung von Fahrzeugen aus Drittländern (z.B. USA, Japan) in der EU benötigt wird. Es bestätigt die technische Übereinstimmung des Fahrzeugs mit den europäischen Vorschriften.',
                    ],
                    [
                        'question' => 'Welche Dokumente werden für die Zulassung eines US-Fahrzeugs in Deutschland benötigt?',
                        'answer' => 'Für die Zulassung eines US-Fahrzeugs in Deutschland benötigen Sie in der Regel: Technisches Datenblatt/Datenbestätigung, Abgasgutachten, ggf. Geräuschgutachten, Zollunbedenklichkeitsbescheinigung, US-Fahrzeugbrief (Title), sowie ggf. Umrüstungsnachweise (Scheinwerfer, Speedometer).',
                    ],
                    [
                        'question' => 'Wie lange dauert die Bearbeitung?',
                        'answer' => 'Nach Zahlungseingang erhalten Sie das Datenblatt in der Regel innerhalb von 3-7 Werktagen per Post. Bei besonders dringenden Anfragen bieten wir auch einen Expressservice an.',
                    ],
                ],
            ],
            [
                'category' => 'Fahrzeugimport',
                'questions' => [
                    [
                        'question' => 'Kann ich ein Fahrzeug als Umzugsgut importieren?',
                        'answer' => 'Ja. Ein Fahrzeug kann als Umzugsgut importiert werden, wenn Sie mindestens 12 aufeinanderfolgende Monate außerhalb der EU gelebt haben und das Fahrzeug mindestens 6 Monate auf Ihren Namen zugelassen war. Umzugsgut ist von Einfuhrumsatzsteuer und Zoll befreit.',
                    ],
                    [
                        'question' => 'Wie hoch ist der Zoll für ein importiertes Fahrzeug?',
                        'answer' => 'Der Zollsatz beträgt 10% für PKW und 22% für LKW auf den Zeitwert des Fahrzeugs. Zusätzlich fällt die Einfuhrumsatzsteuer von 19% an. Diese können Sie durch den Import als Umzugsgut unter bestimmten Voraussetzungen vermeiden.',
                    ],
                    [
                        'question' => 'Was bedeutet die Abgasgutachten-Pflicht?',
                        'answer' => 'Ohne Abgasgutachten wird Ihr Importfahrzeug in eine schlechtere Schadstoffklasse eingestuft, was zu erheblich höherer Kfz-Steuer führen kann. Ein Abgasgutachten kann je nach Hubraumgröße mehrere Hundert Euro an Steuer pro Jahr einsparen.',
                    ],
                ],
            ],
            [
                'category' => 'Preise & Kosten',
                'questions' => [
                    [
                        'question' => 'Was kostet ein Importzertifikat?',
                        'answer' => 'Die Kosten hängen vom Fahrzeugtyp, Herkunftsland und dem benötigten Zertifikatstyp ab. Die Anfrage ist für Sie kostenlos und unverbindlich. Sie erhalten nach Prüfung Ihrer Daten ein konkretes Angebot per E-Mail.',
                    ],
                    [
                        'question' => 'Ist die Anfrage wirklich kostenlos?',
                        'answer' => 'Ja, die Anfrage und das darauffolgende Angebot sind für Sie vollständig kostenlos und unverbindlich. Sie entscheiden nach Erhalt des Angebots, ob Sie das Zertifikat bestellen möchten.',
                    ],
                ],
            ],
        ];

        $this->view->assignMultiple([
            'faqs' => $faqs,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }
}
