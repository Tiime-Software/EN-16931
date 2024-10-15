<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

readonly class BinaryObject
{
    public function __construct(
        public string $content,
        public \Tiime\EN16931\Codelist\MimeCode $mimeCode,
        public string $filename
    ) {
    }
}
