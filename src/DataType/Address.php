<?php

namespace Tiime\EN16931\DataType;

interface Address
{
    public function getLine1(): ?string;

    public function getLine2(): ?string;

    public function getLine3(): ?string;

    public function getCity(): ?string;

    public function getPostCode(): ?string;

    public function getCountrySubdivision(): ?string;

    public function getCountryCode(): \Tiime\EN16931\Codelist\CountryAlpha2Code;
}
