<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\ElectronicAddressIdentifier;
use Tiime\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\TaxRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;

/**
 * BG-4
 * A group of business terms providing information about the Seller.
 */
class Seller
{
    /**
     * BT-27
     * The full formal name by which the Seller is registered in the national registry of legal entities
     * or as a Taxable person or otherwise trades as a person or persons.
     */
    private string $name;

    /**
     * BT-28
     * A name by which the Seller is known, other than Seller name (also known as Business name).
     */
    private ?string $tradingName;

    /**
     * BT-29
     * An identifier of the seller.
     *
     * Identification du Vendeur.
     *
     * @var array<int, SellerIdentifier>
     */
    private array $identifiers;

    /**
     * BT-30
     * An identifier issued by an official registrar that identifies the seller as a legal entity or person.
     */
    private ?LegalRegistrationIdentifier $legalRegistrationIdentifier;

    /**
     * BT-31
     * The Seller's VAT identifier (also known as Seller VAT identification number)
     */
    private ?VatIdentifier $vatIdentifier;

    /**
     * BT-32
     * The local identification (defined by the Seller’s address) of the Seller for tax purposes
     * or a reference that enables the Seller to state his registered tax status.
     */
    private ?TaxRegistrationIdentifier $taxRegistrationIdentifier;

    /**
     * BT-33
     * Additional legal information relevant for the Seller.
     */
    private ?string $additionalLegalInformation;

    /**
     * BT-34
     * Identifies the seller's electronic address to which the application level response to
     * the invoice may be delivered.
     */
    private ?ElectronicAddressIdentifier $electronicAddress;

    /**
     * BG-5
     * A group of business terms providing information about the address of the Seller.
     */
    private SellerPostalAddress $address;

    /**
     * BG-6
     * A group of business terms providing contact information about the Seller.
     */
    private ?SellerContact $contact;

    /**
     * @param array<int, SellerIdentifier> $identifiers
     */
    public function __construct(
        string $name,
        SellerPostalAddress $address,
        array $identifiers,
        ?LegalRegistrationIdentifier $legalRegistrationIdentifier,
        ?VatIdentifier $vatIdentifier,
    ) {
        $this->name = $name;
        $this->address = $address;
        $this->legalRegistrationIdentifier = $legalRegistrationIdentifier;
        $this->vatIdentifier = $vatIdentifier;

        $this->identifiers = [];
        foreach ($identifiers as $identifier) {
            if ($identifier instanceof SellerIdentifier) {
                $this->identifiers[] = $identifier;
            }
        }

        if (empty($this->identifiers) && null === $legalRegistrationIdentifier && null === $vatIdentifier) {
            throw new \Exception('@todo');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): SellerPostalAddress
    {
        return $this->address;
    }

    public function setAddress(SellerPostalAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTradingName(): ?string
    {
        return $this->tradingName;
    }

    public function setTradingName(?string $tradingName): self
    {
        $this->tradingName = $tradingName;

        return $this;
    }

    /**
     * @return array<int, SellerIdentifier>
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * @param array<int, SellerIdentifier> $identifiers
     */
    public function setIdentifiers(array $identifiers): self
    {
        $this->identifiers = $identifiers;

        return $this;
    }

    public function getLegalRegistrationIdentifier(): ?LegalRegistrationIdentifier
    {
        return $this->legalRegistrationIdentifier;
    }

    public function setLegalRegistrationIdentifier(?LegalRegistrationIdentifier $legalRegistrationIdentifier): self
    {
        $this->legalRegistrationIdentifier = $legalRegistrationIdentifier;

        return $this;
    }

    public function getVatIdentifier(): ?VatIdentifier
    {
        return $this->vatIdentifier;
    }

    public function setVatIdentifier(?VatIdentifier $vatIdentifier): self
    {
        $this->vatIdentifier = $vatIdentifier;

        return $this;
    }

    public function getTaxRegistrationIdentifier(): ?TaxRegistrationIdentifier
    {
        return $this->taxRegistrationIdentifier;
    }

    public function setTaxRegistrationIdentifier(?TaxRegistrationIdentifier $taxRegistrationIdentifier): self
    {
        $this->taxRegistrationIdentifier = $taxRegistrationIdentifier;

        return $this;
    }

    public function getAdditionalLegalInformation(): ?string
    {
        return $this->additionalLegalInformation;
    }

    public function setAdditionalLegalInformation(?string $additionalLegalInformation): self
    {
        $this->additionalLegalInformation = $additionalLegalInformation;

        return $this;
    }

    public function getElectronicAddress(): ?ElectronicAddressIdentifier
    {
        return $this->electronicAddress;
    }

    public function setElectronicAddress(?ElectronicAddressIdentifier $electronicAddress): self
    {
        $this->electronicAddress = $electronicAddress;

        return $this;
    }

    public function getContact(): ?SellerContact
    {
        return $this->contact;
    }

    public function setContact(?SellerContact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
