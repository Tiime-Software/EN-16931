<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

readonly class SellerItemIdentifier
{
    public function __construct(
        public string $value
    ) {
    }
}
