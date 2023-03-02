<?php

namespace Tiime\EN16931\BusinessTermsGroup;

/**
 * BG-22
 * A group of business terms providing the monetary totals for the Invoice.
 */
class DocumentTotals
{
    /**
     * BT-106
     * Sum of all Invoice line net amounts in the Invoice.
     */
    private float $sumOfInvoiceLineNetAmount;

    /**
     * BT-107
     * Sum of all allowances on document level in the Invoice.
     */
    private ?float $sumOfAllowancesOnDocumentLevel;

    /**
     * BT-108
     * Sum of all charges on document level in the Invoice.
     */
    private ?float $sumOfChargesOnDocumentLevel;

    /**
     * BT-109
     * The total amount of the Invoice without VAT.
     */
    private float $invoiceTotalAmountWithoutVat;

    /**
     * BT-110
     * The total VAT amount for the Invoice.
     */
    private ?float $invoiceTotalVatAmount;

    /**
     * BT-111
     * The VAT total amount expressed in the accounting currency accepted or required in the country of the Seller.
     */
    private ?float $invoiceTotalVatAmountInAccountingCurrency;

    /**
     * BT-112
     * The total amount of the Invoice with VAT.
     */
    private float $invoiceTotalAmountWithVat;

    /**
     * BT-113
     * The sum of amounts which have been paid in advance.
     */
    private ?float $paidAmount;

    /**
     * BT-114
     * The amount to be added to the invoice total to round the amount to be paid.
     */
    private ?float $roundingAmount;

    /**
     * BT-115
     * The outstanding amount that is requested to be paid.
     */
    private float $amountDueForPayment;

    public function __construct(
        float $sumOfInvoiceLineNetAmount,
        float $invoiceTotalAmountWithoutVat,
        float $invoiceTotalAmountWithVat,
        float $amountDueForPayment,
        ?float $invoiceTotalVatAmountInAccountingCurrency = null,
    ) {
        $this->sumOfInvoiceLineNetAmount = $sumOfInvoiceLineNetAmount;
        $this->invoiceTotalAmountWithoutVat = $invoiceTotalAmountWithoutVat;
        $this->invoiceTotalAmountWithVat = $invoiceTotalAmountWithVat;
        $this->amountDueForPayment = $amountDueForPayment;
        $this->invoiceTotalVatAmount = null;
        $this->invoiceTotalVatAmountInAccountingCurrency = $invoiceTotalVatAmountInAccountingCurrency;
    }

    public function getSumOfInvoiceLineNetAmount(): float
    {
        return $this->sumOfInvoiceLineNetAmount;
    }

    public function setSumOfInvoicesLineNetAmount(float $sumOfInvoiceLineNetAmount): self
    {
        $this->sumOfInvoiceLineNetAmount = $sumOfInvoiceLineNetAmount;

        return $this;
    }

    public function getSumOfAllowancesOnDocumentLevel(): ?float
    {
        return $this->sumOfAllowancesOnDocumentLevel;
    }

    public function setSumOfAllowancesOnDocumentLevel(?float $sumOfAllowancesOnDocumentLevel): self
    {
        $this->sumOfAllowancesOnDocumentLevel = $sumOfAllowancesOnDocumentLevel;

        return $this;
    }

    public function getSumOfChargesOnDocumentLevel(): ?float
    {
        return $this->sumOfChargesOnDocumentLevel;
    }

    public function setSumOfChargesOnDocumentLevel(?float $sumOfChargesOnDocumentLevel): self
    {
        $this->sumOfChargesOnDocumentLevel = $sumOfChargesOnDocumentLevel;

        return $this;
    }

    public function getInvoiceTotalAmountWithoutVat(): float
    {
        return $this->invoiceTotalAmountWithoutVat;
    }

    public function setInvoiceTotalAmountWithoutVat(float $invoiceTotalAmountWithoutVat): self
    {
        $this->invoiceTotalAmountWithoutVat = $invoiceTotalAmountWithoutVat;

        return $this;
    }

    public function getInvoiceTotalVatAmount(): ?float
    {
        return $this->invoiceTotalVatAmount;
    }

    public function setInvoiceTotalVatAmount(?float $invoiceTotalVatAmount): self
    {
        $this->invoiceTotalVatAmount = $invoiceTotalVatAmount;

        return $this;
    }

    public function getInvoiceTotalVatAmountInAccountingCurrency(): ?float
    {
        return $this->invoiceTotalVatAmountInAccountingCurrency;
    }

    public function getInvoiceTotalAmountWithVat(): float
    {
        return $this->invoiceTotalAmountWithVat;
    }

    public function setInvoiceTotalAmountWithVat(float $invoiceTotalAmountWithVat): self
    {
        $this->invoiceTotalAmountWithVat = $invoiceTotalAmountWithVat;

        return $this;
    }

    public function getPaidAmount(): ?float
    {
        return $this->paidAmount;
    }

    public function setPaidAmount(?float $paidAmount): self
    {
        $this->paidAmount = $paidAmount;

        return $this;
    }

    public function getRoundingAmount(): ?float
    {
        return $this->roundingAmount;
    }

    public function setRoundingAmount(?float $roundingAmount): self
    {
        $this->roundingAmount = $roundingAmount;

        return $this;
    }

    public function getAmountDueForPayment(): float
    {
        return $this->amountDueForPayment;
    }

    public function setAmountDueForPayment(float $amountDueForPayment): self
    {
        $this->amountDueForPayment = $amountDueForPayment;

        return $this;
    }
}
