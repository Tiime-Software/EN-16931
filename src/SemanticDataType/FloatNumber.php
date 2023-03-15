<?php

namespace Tiime\EN16931\SemanticDataType;

class FloatNumber extends SemanticDataType
{
    public function __construct(float $value, int $decimals)
    {
        parent::__construct($value, $decimals);
    }
}