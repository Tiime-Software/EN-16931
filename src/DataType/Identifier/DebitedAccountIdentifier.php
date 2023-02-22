<?php

namespace Tiime\EN16931\DataType\Identifier;

class DebitedAccountIdentifier
{
    public function __construct(public readonly string $value)
    {
    }
}
