<?php

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\DataType\VatExoneration;

/**
 * BG-23
 * A group of business terms providing information about VAT breakdown by different
 * categories, rates and exemption reasons.
 */
class VatBreakdown
{
    /**
     * BT-116
     * Sum of all taxable amounts subject to a specific VAT category code and VAT category rate
     * (if the VAT category rate is applicable).
     *
     * Somme de tous les montants soumis à taxes assujettis à un code et à un taux de type de
     * TVA spécifiques (si le Taux de type de TVA est applicable).
     */
    private float $vatCategoryTaxableAmount;

    /**
     * BT-117
     * The total VAT amount for a given VAT category.
     *
     * Montant total de la TVA pour un type donné de TVA.
     */
    private float $vatCategoryTaxAmount;

    /**
     * BT-118
     * Coded identification of a VAT category.
     *
     * Identification codée d'un type de TVA.
     */
    private VatCategory $vatCategoryCode;

    /**
     * BT-119
     * The VAT rate, represented as percentage that applies for the relevant VAT category.
     *
     * Taux de TVA, exprimé sous forme de pourcentage, applicable au type de TVA correspondant.
     */
    private ?float $vatCategoryRate;

    /**
     * BT-120
     * A textual statement of the reason why the amount is exempted from VAT or why no VAT is being charged
     *
     * Énoncé expliquant pourquoi un montant est exonéré de TVA ou pourquoi la TVA n'est pas appliquée.
     */
    private ?string $vatExemptionReasonText;

    /**
     * BT-121
     * A coded statement of the reason for why the amount is exempted from VAT.
     *
     * Énoncé codé expliquant pourquoi un montant est exonéré de TVA.
     */
    private ?VatExoneration $vatExemptionReasonCode;

    public function __construct(
        float $vatCategoryTaxableAmount,
        float $vatCategoryTaxAmount,
        VatCategory $vatCategoryCode,
        ?float $vatCategoryRate = null,
    ) {
        if ($vatCategoryCode !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX && !is_float($vatCategoryRate)) {
            throw new \Exception('@todo');
        }

        $this->vatCategoryTaxableAmount = $vatCategoryTaxableAmount;
        $this->vatCategoryTaxAmount = $vatCategoryTaxAmount;
        $this->vatCategoryCode = $vatCategoryCode;
        $this->vatCategoryRate = $vatCategoryRate;

        $this->vatExemptionReasonText = null;
        $this->vatExemptionReasonCode = null;
    }

    public function getVatCategoryTaxableAmount(): float
    {
        return $this->vatCategoryTaxableAmount;
    }

    public function setVatCategoryTaxableAmount(float $vatCategoryTaxableAmount): self
    {
        $this->vatCategoryTaxableAmount = $vatCategoryTaxableAmount;

        return $this;
    }

    public function getVatCategoryTaxAmount(): float
    {
        return $this->vatCategoryTaxAmount;
    }

    public function setVatCategoryTaxAmount(float $vatCategoryTaxAmount): self
    {
        $this->vatCategoryTaxAmount = $vatCategoryTaxAmount;

        return $this;
    }

    public function getVatCategoryCode(): VatCategory
    {
        return $this->vatCategoryCode;
    }

    public function setVatCategoryCode(VatCategory $vatCategoryCode): self
    {
        if ($vatCategoryCode !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX && !is_float($this->vatCategoryRate)) {
            throw new \Exception('@todo');
        }

        $this->vatCategoryCode = $vatCategoryCode;

        return $this;
    }

    public function getVatCategoryRate(): ?float
    {
        return $this->vatCategoryRate;
    }

    public function setVatCategoryRate(?float $vatCategoryRate): self
    {
        if ($this->vatCategoryCode !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX && !is_float($vatCategoryRate)) {
            throw new \Exception('@todo');
        }

        $this->vatCategoryRate = $vatCategoryRate;

        return $this;
    }

    public function getVatExemptionReasonText(): ?string
    {
        return $this->vatExemptionReasonText;
    }

    public function setVatExemptionReasonText(?string $vatExemptionReasonText): self
    {
        $this->vatExemptionReasonText = $vatExemptionReasonText;

        return $this;
    }

    public function getVatExemptionReasonCode(): ?VatExoneration
    {
        return $this->vatExemptionReasonCode;
    }

    public function setVatExemptionReasonCode(?VatExoneration $vatExemptionReasonCode): self
    {
        $this->vatExemptionReasonCode = $vatExemptionReasonCode;

        return $this;
    }
}
