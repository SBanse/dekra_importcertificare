<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Vendor\DekraImportcertificate\Service\DekraApiService;

/**
 * Controller für die Fahrzeugsuche / VIN-Lookup
 */
class VehicleSearchController extends ActionController
{
    public function __construct(
        private readonly DekraApiService $dekraApiService
    ) {}

    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function searchAction(string $vin = ''): ResponseInterface
    {
        $result = [];
        $error = '';

        if (!empty($vin)) {
            // VIN validieren (17 Zeichen)
            if (!preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', strtoupper($vin))) {
                $error = 'Bitte geben Sie eine gültige Fahrgestellnummer (FIN/VIN, 17 Zeichen) ein.';
            } else {
                $result = $this->dekraApiService->lookupVehicleByVin($vin);
            }
        }

        $this->view->assignMultiple([
            'vin' => $vin,
            'result' => $result,
            'error' => $error,
            'settings' => $this->settings,
        ]);

        return $this->htmlResponse();
    }
}
