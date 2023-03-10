<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\VatCategory;

/**
 * BG-50
 * A group of business terms providing information about the VAT applicable for
 * the goods and services invoiced on the Invoice line.
 */
class LineVatInformation
{
    /**
     * BT-151
     * The VAT category code for the invoiced item.
     *
     * Code de type de TVA applicable à l'article facturé.
     */
    private VatCategory $invoicedItemVatCategoryCode;

    /**
     * BT-152
     * The VAT rate, represented as percentage that applies to the invoiced item.
     */
    private ?float $invoicedItemVatRate;

    public function __construct(VatCategory $invoicedItemVatCategoryCode)
    {
        $this->invoicedItemVatCategoryCode = $invoicedItemVatCategoryCode;
    }

    public function getInvoicedItemVatCategoryCode(): VatCategory
    {
        return $this->invoicedItemVatCategoryCode;
    }

    public function setInvoicedItemVatCategoryCode(VatCategory $invoicedItemVatCategoryCode): self
    {
        $this->invoicedItemVatCategoryCode = $invoicedItemVatCategoryCode;

        return $this;
    }

    public function getInvoicedItemVatRate(): ?float
    {
        return $this->invoicedItemVatRate;
    }

    public function setInvoicedItemVatRate(?float $invoicedItemVatRate): self
    {
        $this->invoicedItemVatRate = $invoicedItemVatRate;

        return $this;
    }
}
