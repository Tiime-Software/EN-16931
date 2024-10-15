<?php

namespace Tiime\EN16931\Codelist\Generator;

final readonly class XlsxReaderResultItem
{
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }

    public function __toString(): string
    {
        return sprintf("case %s = '%s';", $this->name, $this->value);
    }
}
