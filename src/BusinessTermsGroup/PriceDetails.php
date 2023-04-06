<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\SemanticDataType\Quantity;
use Tiime\EN16931\SemanticDataType\UnitPriceAmount;

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
    private UnitPriceAmount $itemNetPrice;

    /**
     * BT-147
     * The total discount subtracted from the Item gross price to calculate the Item net price.
     *
     */
    private ?UnitPriceAmount $itemPriceDiscount;

    /**
     * BT-148
     * The unit price, exclusive of VAT, before subtracting Item price discount.
     *
     */
    private ?UnitPriceAmount $itemGrossPrice;

    /**
     * BT-149
     * The number of item units to which the price applies.
     *
     */
    private ?Quantity $itemPriceBaseQuantity;

    /**
     * BT-150
     * The Item price base quantity unit of measure shall be the same as the Invoiced quantity unit of measure (BT-130).
     *
     */
    private ?UnitOfMeasurement $itemPriceBaseQuantityUnitOfMeasureCode;

    public function __construct(float $itemNetPrice)
    {
        if ($itemNetPrice < 0) {
            throw new \Exception('@todo');
        }

        $this->itemNetPrice = new UnitPriceAmount($itemNetPrice);
        $this->itemPriceDiscount = null;
        $this->itemGrossPrice = null;
        $this->itemPriceBaseQuantity = null;
        $this->itemPriceBaseQuantityUnitOfMeasureCode = null;
    }

    public function getItemNetPrice(): float
    {
        return $this->itemNetPrice->getValueRounded();
    }

    public function getItemPriceDiscount(): ?float
    {
        return $this->itemPriceDiscount?->getValueRounded();
    }

    public function setItemPriceDiscount(?float $itemPriceDiscount): self
    {
        $this->itemPriceDiscount = \is_float($itemPriceDiscount) ? new UnitPriceAmount($itemPriceDiscount) : null;

        return $this;
    }

    public function getItemGrossPrice(): ?float
    {
        return $this->itemGrossPrice?->getValueRounded();
    }

    public function setItemGrossPrice(?float $itemGrossPrice): self
    {
        if ($itemGrossPrice < 0) {
            throw new \Exception('@todo');
        }

        $this->itemGrossPrice = \is_float($itemGrossPrice) ? new UnitPriceAmount($itemGrossPrice) : null;

        return $this;
    }

    public function getItemPriceBaseQuantity(): ?float
    {
        return $this->itemPriceBaseQuantity?->getValueRounded();
    }

    public function setItemPriceBaseQuantity(?float $itemPriceBaseQuantity): self
    {
        $this->itemPriceBaseQuantity = \is_float($itemPriceBaseQuantity) ? new Quantity($itemPriceBaseQuantity) : null;

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
