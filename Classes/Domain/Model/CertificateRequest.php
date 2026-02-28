<?php

declare(strict_types=1);

namespace Vendor\DekraImportcertificate\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Model für eine Zertifikatsanfrage
 */
class CertificateRequest extends AbstractEntity
{
    // Fahrzeugdaten
    protected string $vin = '';
    protected string $vehicleMake = '';
    protected string $vehicleModel = '';
    protected int $vehicleYear = 0;
    protected string $vehicleCategory = ''; // PKW, LKW, Motorrad, etc.
    protected string $importCountry = ''; // Herkunftsland
    protected string $targetCountry = 'DE'; // Zielland
    protected string $certificateType = ''; // Vollgutachten, Einzelgenehmigung, etc.

    // Kundendaten
    protected string $salutation = '';
    protected string $firstName = '';
    protected string $lastName = '';
    protected string $company = '';
    protected string $street = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = 'DE';
    protected string $phone = '';
    protected string $email = '';

    // Weitere Angaben
    protected string $additionalInfo = '';
    protected bool $urgentProcessing = false;
    protected bool $privacyAccepted = false;
    protected bool $termsAccepted = false;

    // Status
    protected string $status = 'new'; // new, pending, processing, completed, rejected
    protected string $referenceNumber = '';
    protected \DateTime $requestDate;

    public function __construct()
    {
        $this->requestDate = new \DateTime();
    }

    // Getter & Setter

    public function getVin(): string { return $this->vin; }
    public function setVin(string $vin): void { $this->vin = strtoupper(trim($vin)); }

    public function getVehicleMake(): string { return $this->vehicleMake; }
    public function setVehicleMake(string $vehicleMake): void { $this->vehicleMake = $vehicleMake; }

    public function getVehicleModel(): string { return $this->vehicleModel; }
    public function setVehicleModel(string $vehicleModel): void { $this->vehicleModel = $vehicleModel; }

    public function getVehicleYear(): int { return $this->vehicleYear; }
    public function setVehicleYear(int $vehicleYear): void { $this->vehicleYear = $vehicleYear; }

    public function getVehicleCategory(): string { return $this->vehicleCategory; }
    public function setVehicleCategory(string $vehicleCategory): void { $this->vehicleCategory = $vehicleCategory; }

    public function getImportCountry(): string { return $this->importCountry; }
    public function setImportCountry(string $importCountry): void { $this->importCountry = $importCountry; }

    public function getTargetCountry(): string { return $this->targetCountry; }
    public function setTargetCountry(string $targetCountry): void { $this->targetCountry = $targetCountry; }

    public function getCertificateType(): string { return $this->certificateType; }
    public function setCertificateType(string $certificateType): void { $this->certificateType = $certificateType; }

    public function getSalutation(): string { return $this->salutation; }
    public function setSalutation(string $salutation): void { $this->salutation = $salutation; }

    public function getFirstName(): string { return $this->firstName; }
    public function setFirstName(string $firstName): void { $this->firstName = $firstName; }

    public function getLastName(): string { return $this->lastName; }
    public function setLastName(string $lastName): void { $this->lastName = $lastName; }

    public function getFullName(): string { return trim($this->firstName . ' ' . $this->lastName); }

    public function getCompany(): string { return $this->company; }
    public function setCompany(string $company): void { $this->company = $company; }

    public function getStreet(): string { return $this->street; }
    public function setStreet(string $street): void { $this->street = $street; }

    public function getZip(): string { return $this->zip; }
    public function setZip(string $zip): void { $this->zip = $zip; }

    public function getCity(): string { return $this->city; }
    public function setCity(string $city): void { $this->city = $city; }

    public function getCountry(): string { return $this->country; }
    public function setCountry(string $country): void { $this->country = $country; }

    public function getPhone(): string { return $this->phone; }
    public function setPhone(string $phone): void { $this->phone = $phone; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getAdditionalInfo(): string { return $this->additionalInfo; }
    public function setAdditionalInfo(string $additionalInfo): void { $this->additionalInfo = $additionalInfo; }

    public function isUrgentProcessing(): bool { return $this->urgentProcessing; }
    public function setUrgentProcessing(bool $urgentProcessing): void { $this->urgentProcessing = $urgentProcessing; }

    public function isPrivacyAccepted(): bool { return $this->privacyAccepted; }
    public function setPrivacyAccepted(bool $privacyAccepted): void { $this->privacyAccepted = $privacyAccepted; }

    public function isTermsAccepted(): bool { return $this->termsAccepted; }
    public function setTermsAccepted(bool $termsAccepted): void { $this->termsAccepted = $termsAccepted; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function getReferenceNumber(): string { return $this->referenceNumber; }
    public function setReferenceNumber(string $referenceNumber): void { $this->referenceNumber = $referenceNumber; }

    public function getRequestDate(): \DateTime { return $this->requestDate; }
    public function setRequestDate(\DateTime $requestDate): void { $this->requestDate = $requestDate; }

    /**
     * Generiert eine eindeutige Referenznummer
     */
    public function generateReferenceNumber(): string
    {
        $this->referenceNumber = 'DEKRA-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        return $this->referenceNumber;
    }
}
