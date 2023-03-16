<?php

namespace Tiime\EN16931\SemanticDataType;

class DecimalNumber implements Number
{
    public function __construct(private readonly float $value, private readonly ?int $decimals = null)
    {
        if(
            $this->decimals !== null
            && !preg_match(sprintf('/^-?\d+(\.\d{1,%s})?$/', $decimals), (string) $value)
        ) {
            throw new \Exception('@todo');
        }

        // TODO : bcmath
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getValueRounded(): float
    {
        if (null === $this->decimals) {
            return $this->value;
        }

        return round($this->value, $this->decimals);
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
        return (string) $this->getValueRounded();
    }
}