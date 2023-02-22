<?php

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-32
 * A group of business terms providing information about properties of the goods and services invoiced.
 */
class ItemAttribute
{
    /**
     * BT-160
     * Item attribute name.
     */
    private string $name;

    /**
     * BT-161
     * Item attribute value.
     */
    private string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
