<?php

declare(strict_types=1);

namespace Tiime\EN16931\BusinessTermsGroup;

use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\DataType\VatExoneration;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\DecimalNumber;
use Tiime\EN16931\SemanticDataType\IntegerNumber;
use Tiime\EN16931\SemanticDataType\Percentage;

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
    private Amount $vatCategoryTaxableAmount;

    /**
     * BT-117
     * The total VAT amount for a given VAT category.
     *
     * Montant total de la TVA pour un type donné de TVA.
     */
    private Amount $vatCategoryTaxAmount;

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
    private ?Percentage $vatCategoryRate;

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
        ?string $vatExemptionReasonText = null,
        ?VatExoneration $vatExemptionReasonCode = null,
    ) {
        if ($vatCategoryCode !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX xor is_float($vatCategoryRate)) {
            throw new \Exception('@todo');
        }

        if (
            in_array(
                $vatCategoryCode,
                [
                    VatCategory::ZERO_RATED_GOODS,
                    VatCategory::EXEMPT_FROM_TAX,
                    VatCategory::VAT_REVERSE_CHARGE,
                    VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES,
                    VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED,
                    VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX
                ]
            )
            && $vatCategoryTaxAmount !== 0.0
        ) {
            throw new \Exception('@todo : BR-genericVAT-9');
        }

        $this->vatCategoryTaxableAmount = new Amount($vatCategoryTaxableAmount);
        $this->vatCategoryTaxAmount = new Amount($vatCategoryTaxAmount);
        $this->vatCategoryCode = $vatCategoryCode;
        $this->vatCategoryRate = is_float($vatCategoryRate) ? new Percentage($vatCategoryRate) : $vatCategoryRate;
        $this->vatExemptionReasonText = $vatExemptionReasonText;
        $this->vatExemptionReasonCode = $vatExemptionReasonCode;

        $BT119_divided_100 = ($this->vatCategoryRate ?? new Percentage(0.00))->divide(new IntegerNumber(100));
        $BT119_100_multiply_BT117 = $this->vatCategoryTaxableAmount
            ->multiply(new DecimalNumber($BT119_divided_100), Amount::DECIMALS);

        if ($this->vatCategoryTaxAmount->getValueRounded() !== $BT119_100_multiply_BT117) {
            throw new \Exception('@todo : BR-CO-17');
        }

        $this->checkExemptionReason();
    }

    public function getVatCategoryTaxableAmount(): float
    {
        return $this->vatCategoryTaxableAmount->getValue();
    }

    public function getVatCategoryTaxAmount(): float
    {
        return $this->vatCategoryTaxAmount->getValue();
    }

    public function getVatCategoryCode(): VatCategory
    {
        return $this->vatCategoryCode;
    }

    public function getVatCategoryRate(): ?float
    {
        return $this->vatCategoryRate?->getValue();
    }

    public function getVatExemptionReasonText(): ?string
    {
        return $this->vatExemptionReasonText;
    }

    public function getVatExemptionReasonCode(): ?VatExoneration
    {
        return $this->vatExemptionReasonCode;
    }

    private function checkExemptionReason(): void
    {
        $noExemptionCategories = [
            VatCategory::STANDARD_RATE,
            VatCategory::ZERO_RATED_GOODS,
            VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX,
            VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
        ];

        $shallHaveExemptionReason = !in_array($this->vatCategoryCode, $noExemptionCategories);
        $hasExemptionReason = is_string($this->vatExemptionReasonText)
            || $this->vatExemptionReasonCode instanceof VatExoneration;

        if ($shallHaveExemptionReason !== $hasExemptionReason) {
            throw new \Exception('@todo BR-genericVAT-10');
        }
    }
}
