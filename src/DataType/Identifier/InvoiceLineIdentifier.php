<?php

namespace Tiime\EN16931\DataType\Identifier;

class InvoiceLineIdentifier
{
    public function __construct(public readonly string $value)
    {
    }
}
