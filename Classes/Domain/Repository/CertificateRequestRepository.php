<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Vendor\DekraImportcertificate\Domain\Model\CertificateRequest;

class CertificateRequestRepository extends Repository
{
    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_DESCENDING,
    ];

    /**
     * Findet Anfrage anhand der Referenznummer
     */
    public function findByReferenceNumber(string $referenceNumber): ?CertificateRequest
    {
        $query = $this->createQuery();
        $query->matching($query->equals('referenceNumber', $referenceNumber));
        return $query->execute()->getFirst();
    }

    /**
     * Findet alle Anfragen eines Kunden per E-Mail
     */
    public function findByEmail(string $email): array
    {
        $query = $this->createQuery();
        $query->matching($query->equals('email', $email));
        return $query->execute()->toArray();
    }

    /**
     * Findet alle Anfragen nach Status
     */
    public function findByStatus(string $status): array
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('status', $status));
        return $query->execute()->toArray();
    }
}
