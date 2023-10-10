<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

class BinaryObject
{
    public function __construct(
        public readonly string $content,
        public readonly MimeCode $mimeCode,
        public readonly string $filename
    ) {
    }
}
