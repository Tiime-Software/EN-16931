<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

readonly class VatIdentifier
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
