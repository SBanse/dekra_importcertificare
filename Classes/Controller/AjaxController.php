<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use Vendor\DekraImportcertificate\Service\DekraApiService;

/**
 * AJAX Controller für Frontend-Anfragen
 */
class AjaxController
{
    public function __construct(
        private readonly DekraApiService $dekraApiService
    ) {}

    /**
     * VIN/FIN Lookup per AJAX
     */
    public function vehicleLookup(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode((string)$request->getBody(), true);
        $vin = trim(strtoupper($body['vin'] ?? ''));

        if (empty($vin) || !preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin)) {
            return new JsonResponse([
                'found' => false,
                'error' => 'Ungültige FIN',
            ], 400);
        }

        $data = $this->dekraApiService->lookupVehicleByVin($vin);

        return new JsonResponse($data ?: ['found' => false]);
    }
}
