<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\Identifier\VatIdentifier;

/**
 * BG-11
 * A group of business terms providing information about the Seller's tax representative.
 */
class SellerTaxRepresentativeParty
{
    /**
     * BT-62
     * The full name of the Seller's tax representative party.
     */
    private string $name;

    /**
     * BT-63
     * The VAT identifier of the Seller's tax representative party
     */
    private VatIdentifier $vatIdentifier;

    private SellerTaxRepresentativePostalAddress $address;

    public function __construct(
        string $name,
        VatIdentifier $vatIdentifier,
        SellerTaxRepresentativePostalAddress $address
    ) {
        $this->name = $name;
        $this->vatIdentifier = $vatIdentifier;
        $this->address = $address;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVatIdentifier(): VatIdentifier
    {
        return $this->vatIdentifier;
    }

    public function getAddress(): SellerTaxRepresentativePostalAddress
    {
        return $this->address;
    }
}
