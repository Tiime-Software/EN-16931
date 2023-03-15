<?php

namespace Tiime\EN16931\SemanticDataType;

abstract class SemanticDataType
{
    public function __construct(private readonly float $value, private readonly int $decimals)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getValueRounded(): float
    {
        return round($this->value, $this->decimals);
    }

    public function add(SemanticDataType $semanticDataType, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() + $semanticDataType->getValue();
        }

        return round($this->getValue() + $semanticDataType->getValue(), $decimals ?? 0);
    }

    public function subtract(SemanticDataType $semanticDataType, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() - $semanticDataType->getValue();
        }

        return round($this->getValue() - $semanticDataType->getValue(), $decimals ?? 0);
    }

    public function multiply(SemanticDataType $semanticDataType, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() * $semanticDataType->getValue();
        }

        return round($this->getValue() * $semanticDataType->getValue(), $decimals ?? 0);
    }

    public function divide(SemanticDataType $semanticDataType, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() / $semanticDataType->getValue();
        }

        return round($this->getValue() / $semanticDataType->getValue(), $decimals ?? 0);
    }

    public function __toString()
    {
        return $this->getValueRounded();
    }
}