<?php

namespace Tiime\EN16931\DataType\Identifier;

class PaymentAccountIdentifier
{
    public function __construct(public readonly string $value)
    {
    }
}
