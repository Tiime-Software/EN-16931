<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\DecimalNumber;

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
    private Amount $sumOfInvoiceLineNetAmount;

    /**
     * BT-107
     * Sum of all allowances on document level in the Invoice.
     */
    private ?Amount $sumOfAllowancesOnDocumentLevel;

    /**
     * BT-108
     * Sum of all charges on document level in the Invoice.
     */
    private ?Amount $sumOfChargesOnDocumentLevel;

    /**
     * BT-109
     * The total amount of the Invoice without VAT.
     */
    private Amount $invoiceTotalAmountWithoutVat;

    /**
     * BT-110
     * The total VAT amount for the Invoice.
     */
    private ?Amount $invoiceTotalVatAmount;

    /**
     * BT-111
     * The VAT total amount expressed in the accounting currency accepted or required in the country of the Seller.
     */
    private ?Amount $invoiceTotalVatAmountInAccountingCurrency;

    /**
     * BT-112
     * The total amount of the Invoice with VAT.
     */
    private Amount $invoiceTotalAmountWithVat;

    /**
     * BT-113
     * The sum of amounts which have been paid in advance.
     */
    private ?Amount $paidAmount;

    /**
     * BT-114
     * The amount to be added to the invoice total to round the amount to be paid.
     */
    private ?Amount $roundingAmount;

    /**
     * BT-115
     * The outstanding amount that is requested to be paid.
     */
    private Amount $amountDueForPayment;

    public function __construct(
        float $sumOfInvoiceLineNetAmount,
        float $invoiceTotalAmountWithoutVat,
        float $invoiceTotalAmountWithVat,
        float $amountDueForPayment,
        ?float $invoiceTotalVatAmountInAccountingCurrency = null,
        ?float $invoiceTotalVatAmount = null,
        ?float $paidAmount = null,
        ?float $roundingAmount = null,
        ?float $sumOfAllowancesOnDocumentLevel = null,
        ?float $sumOfChargesOnDocumentLevel = null,
    ) {
        $this->sumOfInvoiceLineNetAmount = new Amount($sumOfInvoiceLineNetAmount);
        $this->invoiceTotalAmountWithoutVat = new Amount($invoiceTotalAmountWithoutVat);
        $this->invoiceTotalAmountWithVat = new Amount($invoiceTotalAmountWithVat);
        $this->amountDueForPayment = new Amount($amountDueForPayment);

        $this->invoiceTotalVatAmountInAccountingCurrency = $invoiceTotalVatAmountInAccountingCurrency !== null ?
            new Amount($invoiceTotalVatAmountInAccountingCurrency) : $invoiceTotalVatAmountInAccountingCurrency;
        $this->invoiceTotalVatAmount = $invoiceTotalVatAmount !== null ?
            new Amount($invoiceTotalVatAmount) : $invoiceTotalVatAmount;
        $this->paidAmount = $paidAmount !== null ? new Amount($paidAmount) : $paidAmount;
        $this->roundingAmount = $roundingAmount !== null ? new Amount($roundingAmount) : $roundingAmount;
        $this->sumOfAllowancesOnDocumentLevel = $sumOfAllowancesOnDocumentLevel !== null ?
            new Amount($sumOfAllowancesOnDocumentLevel) : $sumOfAllowancesOnDocumentLevel;
        $this->sumOfChargesOnDocumentLevel = $sumOfChargesOnDocumentLevel !== null ?
            new Amount($sumOfChargesOnDocumentLevel) : $sumOfChargesOnDocumentLevel;

        $BT109_plus_BT110 = $this->invoiceTotalAmountWithoutVat
            ->add($this->invoiceTotalVatAmount ?? new Amount(0.00), Amount::DECIMALS);

        if ($this->invoiceTotalAmountWithVat->getValueRounded() !== $BT109_plus_BT110) {
            throw new \Exception('@todo : BR-CO-15');
        }

        $BT112_minus_BT113 = $this->invoiceTotalAmountWithVat
            ->subtract($this->paidAmount ?? new Amount(0.00));
        $BT112_BT113_plus_BT114 = (new DecimalNumber($BT112_minus_BT113))
            ->add($this->roundingAmount ?? new Amount(0.00), Amount::DECIMALS);

        if ($this->amountDueForPayment->getValueRounded() !== $BT112_BT113_plus_BT114) {
            throw new \Exception('@todo : BR-CO-16');
        }
    }

    public function getSumOfInvoiceLineNetAmount(): Amount
    {
        return $this->sumOfInvoiceLineNetAmount;
    }

    public function getSumOfAllowancesOnDocumentLevel(): ?Amount
    {
        return $this->sumOfAllowancesOnDocumentLevel;
    }

    public function getSumOfChargesOnDocumentLevel(): ?Amount
    {
        return $this->sumOfChargesOnDocumentLevel;
    }

    public function getInvoiceTotalAmountWithoutVat(): Amount
    {
        return $this->invoiceTotalAmountWithoutVat;
    }

    public function getInvoiceTotalVatAmount(): ?Amount
    {
        return $this->invoiceTotalVatAmount;
    }

    public function getInvoiceTotalVatAmountInAccountingCurrency(): ?Amount
    {
        return $this->invoiceTotalVatAmountInAccountingCurrency;
    }

    public function getInvoiceTotalAmountWithVat(): Amount
    {
        return $this->invoiceTotalAmountWithVat;
    }

    public function getPaidAmount(): ?Amount
    {
        return $this->paidAmount;
    }

    public function getRoundingAmount(): ?Amount
    {
        return $this->roundingAmount;
    }

    public function getAmountDueForPayment(): Amount
    {
        return $this->amountDueForPayment;
    }
}
