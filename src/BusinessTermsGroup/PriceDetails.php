<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\UnitOfMeasurement;

/**
 * BG-29
 * A group of business terms providing information about the price applied for
 * the goods and services invoiced on the Invoice line.
 */
class PriceDetails
{
    /**
     * BT-146
     * The price of an item, exclusive of VAT, after subtracting item price discount.
     *
     */
    private float $itemNetPrice;

    /**
     * BT-147
     * The total discount subtracted from the Item gross price to calculate the Item net price.
     *
     */
    private ?float $itemPriceDiscount;

    /**
     * BT-148
     * The unit price, exclusive of VAT, before subtracting Item price discount.
     *
     */
    private ?float $itemGrossPrice;

    /**
     * BT-149
     * The number of item units to which the price applies.
     *
     */
    private ?float $itemPriceBaseQuantity;

    /**
     * BT-150
     * The Item price base quantity unit of measure shall be the same as the Invoiced quantity unit of measure (BT-130).
     *
     */
    private ?UnitOfMeasurement $itemPriceBaseQuantityUnitOfMeasureCode;

    public function __construct(float $itemNetPrice)
    {
        $this->itemNetPrice = $itemNetPrice;
        $this->itemPriceDiscount = null;
        $this->itemGrossPrice = null;
        $this->itemPriceBaseQuantity = null;
        $this->itemPriceBaseQuantityUnitOfMeasureCode = null;
    }

    public function getItemNetPrice(): float
    {
        return $this->itemNetPrice;
    }

    public function setItemNetPrice(float $itemNetPrice): self
    {
        $this->itemNetPrice = $itemNetPrice;

        return $this;
    }

    public function getItemPriceDiscount(): ?float
    {
        return $this->itemPriceDiscount;
    }

    public function setItemPriceDiscount(?float $itemPriceDiscount): self
    {
        $this->itemPriceDiscount = $itemPriceDiscount;

        return $this;
    }

    public function getItemGrossPrice(): ?float
    {
        return $this->itemGrossPrice;
    }

    public function setItemGrossPrice(?float $itemGrossPrice): self
    {
        $this->itemGrossPrice = $itemGrossPrice;

        return $this;
    }

    public function getItemPriceBaseQuantity(): ?float
    {
        return $this->itemPriceBaseQuantity;
    }

    public function setItemPriceBaseQuantity(?float $itemPriceBaseQuantity): self
    {
        $this->itemPriceBaseQuantity = $itemPriceBaseQuantity;

        return $this;
    }

    public function getItemPriceBaseQuantityUnitOfMeasureCode(): ?UnitOfMeasurement
    {
        return $this->itemPriceBaseQuantityUnitOfMeasureCode;
    }

    public function setItemPriceBaseQuantityUnitOfMeasureCode(
        ?UnitOfMeasurement $itemPriceBaseQuantityUnitOfMeasureCode
    ): self {
        $this->itemPriceBaseQuantityUnitOfMeasureCode = $itemPriceBaseQuantityUnitOfMeasureCode;

        return $this;
    }
}
