<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType\Identifier;

use Tiime\EN16931\Codelist\ItemTypeCodeUNTDID7143;

readonly class ItemClassificationIdentifier
{
    public function __construct(
        public string $value,
        public ItemTypeCodeUNTDID7143 $scheme,
        public ?string $version = null,
    ) {
    }
}
