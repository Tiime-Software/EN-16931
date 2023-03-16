<?php

namespace Tiime\EN16931\SemanticDataType;

class IntegerNumber implements Number
{
    public function __construct(private readonly int $value)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function add(Number $number, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() + $number->getValue();
        }

        return round($this->getValue() + $number->getValue(), $decimals ?? 0);
    }

    public function subtract(Number $number, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() - $number->getValue();
        }

        return round($this->getValue() - $number->getValue(), $decimals ?? 0);
    }

    public function multiply(Number $number, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() * $number->getValue();
        }

        return round($this->getValue() * $number->getValue(), $decimals ?? 0);
    }

    public function divide(Number $number, ?int $decimals = null): float
    {
        if (null === $decimals) {
            return $this->getValue() / $number->getValue();
        }

        return round($this->getValue() / $number->getValue(), $decimals ?? 0);
    }

    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}