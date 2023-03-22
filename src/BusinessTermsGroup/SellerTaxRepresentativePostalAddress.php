<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\CountryAlpha2Code;

/**
 * BG-12
 * A group of business terms providing information about the postal address for the tax representative party.
 */
class SellerTaxRepresentativePostalAddress
{
    /**
     * BT-64
     * The main address line in an address.
     */
    private ?string $line1;

    /**
     * BT-65
     * An additional address line in an address that can be used to give further details supplementing the main line.
     */
    private ?string $line2;

    /**
     * BT-164
     * An additional address line in an address that can be used to give further details supplementing the main line.
     */
    private ?string $line3;

    /**
     * BT-66
     * The common name of the city, town or village, where the Seller address is located.
     */
    private ?string $city;

    /**
     * BT-67
     * The identifier for an addressable group of properties according to the relevant postal service.
     */
    private ?string $postCode;

    /**
     * BT-68
     * The subdivision of a country.
     */
    private ?string $countrySubdivision;

    /**
     * BT-69
     * A code that identifies the country.
     */
    private CountryAlpha2Code $countryCode;

    public function __construct(CountryAlpha2Code $countryCode)
    {
        $this->line1 = null;
        $this->line2 = null;
        $this->line3 = null;
        $this->city = null;
        $this->postCode = null;
        $this->countrySubdivision = null;
        $this->countryCode = $countryCode;
    }

    public function getLine1(): ?string
    {
        return $this->line1;
    }

    public function setLine1(?string $line1): void
    {
        $this->line1 = $line1;
    }

    public function getLine2(): ?string
    {
        return $this->line2;
    }

    public function setLine2(?string $line2): void
    {
        $this->line2 = $line2;
    }

    public function getLine3(): ?string
    {
        return $this->line3;
    }

    public function setLine3(?string $line3): void
    {
        $this->line3 = $line3;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): void
    {
        $this->postCode = $postCode;
    }

    public function getCountrySubdivision(): ?string
    {
        return $this->countrySubdivision;
    }

    public function setCountrySubdivision(?string $countrySubdivision): void
    {
        $this->countrySubdivision = $countrySubdivision;
    }

    public function getCountryCode(): CountryAlpha2Code
    {
        return $this->countryCode;
    }
}
