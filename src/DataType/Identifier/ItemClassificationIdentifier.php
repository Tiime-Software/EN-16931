<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\ItemTypeCodeUNTDID7143;

class ItemClassificationIdentifier
{
    public function __construct(
        public readonly string $value,
        public readonly ItemTypeCodeUNTDID7143 $scheme,
        public readonly ?string $version = null,
    ) {
    }
}
