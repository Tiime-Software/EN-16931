<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

readonly class BuyerItemIdentifier
{
    public function __construct(public string $value)
    {
    }
}
