<?php

declare(strict_types=1);

namespace Tiime\EN16931;

use Tiime\EN16931\BusinessTermsGroup\AdditionalSupportingDocument;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\DeliverToAddress;
use Tiime\EN16931\BusinessTermsGroup\DeliveryInformation;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoiceNote;
use Tiime\EN16931\BusinessTermsGroup\InvoicingPeriod;
use Tiime\EN16931\BusinessTermsGroup\Payee;
use Tiime\EN16931\BusinessTermsGroup\PaymentInstructions;
use Tiime\EN16931\BusinessTermsGroup\PrecedingInvoice;
use Tiime\EN16931\BusinessTermsGroup\ProcessControl;
use Tiime\EN16931\BusinessTermsGroup\Seller;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\DateCode2005;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\ObjectIdentifier;
use Tiime\EN16931\DataType\Identifier\TaxRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\Reference\ContractReference;
use Tiime\EN16931\DataType\Reference\DespatchAdviceReference;
use Tiime\EN16931\DataType\Reference\ProjectReference;
use Tiime\EN16931\DataType\Reference\PurchaseOrderReference;
use Tiime\EN16931\DataType\Reference\ReceivingAdviceReference;
use Tiime\EN16931\DataType\Reference\SalesOrderReference;
use Tiime\EN16931\DataType\Reference\TenderOrLotReference;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\DecimalNumber;
use Tiime\EN16931\SemanticDataType\Percentage;

class Invoice
{
    /**
     * BT-1
     * A unique identification of the Invoice.
     */
    private InvoiceIdentifier $number;

    /**
     * BT-2
     * The date when the Invoice was issued.
     */
    private \DateTimeInterface $issueDate;

    /**
     * BT-3
     * A code specifying the functional type of the Invoice.
     *
     */
    private InvoiceTypeCode $typeCode;

    /**
     * BT-5
     * The currency in which all Invoice amounts are given, except for the Total VAT amount in accounting currency.
     */
    private CurrencyCode $currencyCode;

    /**
     * BT-6
     * The currency used for VAT accounting and reporting purposes as accepted or required in the country of the Seller.
     */
    private ?CurrencyCode $vatAccountingCurrencyCode;

    /**
     * BT-7
     * The date when the VAT becomes accountable for the Seller and for the Buyer in so far as
     * that date can be determined and differs from the date of issue of the invoice, according to the VAT directive.
     */
    private ?\DateTimeInterface $valueAddedTaxPointDate;

    /**
     * BT-8
     * The code of the date when the VAT becomes accountable for the Seller and for the Buyer.
     */
    private ?DateCode2005 $valueAddedTaxPointDateCode;

    /**
     * BT-9
     * The date when the payment is due.
     */
    private ?\DateTimeInterface $paymentDueDate;

    /**
     * BT-10
     * An identifier assigned by the Buyer used for internal routing purposes.
     *
     * Identifiant assigné par l'Acheteur et destiné au routage de la facture en interne.
     */
    private ?string $buyerReference;

    /**
     * BT-11
     * The identification of the project the invoice refers to.
     */
    private ?ProjectReference $projectReference;

    /**
     * BT-12
     * The identification of a contract.
     */
    private ?ContractReference $contractReference;

    /**
     * BT-13
     * An identifier of a referenced purchase order, issued by the Buyer.
     */
    private ?PurchaseOrderReference $purchaseOrderReference;

    /**
     * BT-14
     * An identifier of a referenced sales order, issued by the Seller.
     */
    private ?SalesOrderReference $salesOrderReference;

    /**
     * BT-15
     * An identifier of a referenced receiving advice.
     */
    private ?ReceivingAdviceReference $receivingAdviceReference;

    /**
     * BT-16
     * An identifier of a referenced despatch advice.
     */
    private ?DespatchAdviceReference $despatchAdviceReference;

    /**
     * BT-17
     * The identification of the call for tender or lot the invoice relates to.
     */
    private ?TenderOrLotReference $tenderOrLotReference;

    /**
     * BT-18
     * An identifier for an object on which the invoice is based, given by the Seller.
     */
    private ?ObjectIdentifier $objectIdentifier;

    /**
     * BT-19
     * A textual value that specifies where to book the relevant data into the Buyer's financial accounts.
     */
    private ?string $buyerAccountingReference;

    /**
     * BT-20
     * A textual description of the payment terms that apply to
     * the amount due for payment (Including description of possible penalties).
     */
    private ?string $paymentTerms;

    /**
     * BG-1
     * A group of business terms providing textual notes that are relevant for the invoice,
     * together with an indication of the note subject.
     *
     * @var array<int, InvoiceNote>
     */
    private array $invoiceNote;

    /**
     * BG-2
     * A group of business terms providing information on the business process and rules applicable to the Invoice
     * document.
     */
    private ProcessControl $processControl;

    /**
     * BG-3
     * A group of business terms providing information on one or more preceding Invoices.
     *
     * @var array<int, PrecedingInvoice>
     */
    private array $precedingInvoices;

    /**
     * BG-4
     * A group of business terms providing information about the Seller.
     */
    private Seller $seller;

    /**
     * BG-7
     * A group of business terms providing information about the Buyer.
     */
    private Buyer $buyer;

    /**
     * BG-10
     * A group of business terms providing information about the Payee, i.e. the role that receives the payment.
     */
    private ?Payee $payee;

    /**
     * BG-11
     * A group of business terms providing information about the Seller's tax representative.
     */
    private ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty;

    /**
     * BG-13
     * A group of business terms providing information about where and when the goods and services invoiced are
     * delivered.
     */
    private ?DeliveryInformation $deliveryInformation;

    /**
     * BG-16
     * A group of business terms providing information about the payment.
     */
    private ?PaymentInstructions $paymentInstructions;

    /**
     * BG-20
     * A group of business terms providing information about allowances applicable to the Invoice as a whole.
     *
     * @var array<int, DocumentLevelAllowance>
     */
    private array $documentLevelAllowances;

    /**
     * BG-21
     * A group of business terms providing information about charges and taxes other than VAT,
     * applicable to the Invoice as a whole.
     *
     * @var array<int, DocumentLevelCharge>
     */
    private array $documentLevelCharges;

    /**
     * BG-22
     * A group of business terms providing the monetary totals for the Invoice.
     */
    private DocumentTotals $documentTotals;

    /**
     * BG-23
     * A group of business terms providing information about VAT breakdown by different
     * categories, rates and exemption reasons.
     *
     * @var array<int, VatBreakdown>
     */
    private array $vatBreakdowns;

    /**
     * BG-24
     * A group of business terms providing information about additional supporting documents substantiating
     * the claims made in the Invoice.
     *
     * @var array<int, AdditionalSupportingDocument>
     */
    private array $additionalSupportingDocuments;

    /**
     * BG-25
     * A group of business terms providing information on individual Invoice lines.
     *
     * @var array<int, InvoiceLine>
     */
    private array $invoiceLines;

    /**
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     */
    public function __construct(
        InvoiceIdentifier $number,
        \DateTimeInterface $issueDate,
        InvoiceTypeCode $typeCode,
        CurrencyCode $currencyCode,
        ProcessControl $processControl,
        Seller $seller,
        Buyer $buyer,
        ?CurrencyCode $vatAccountingCurrencyCode,
        DocumentTotals $documentTotals,
        array $vatBreakdowns,
        array $invoiceLines,
        ?\DateTimeInterface $valueAddedTaxPointDate,
        ?DateCode2005 $valueAddedTaxPointDateCode,
        ?\DateTimeInterface $paymentDueDate,
        ?string $paymentTerms,
        array $documentLevelAllowances,
        array $documentLevelCharges,
        ?DeliveryInformation $deliveryInformation = null,
        ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty = null
    ) {
        /** BR-S-1 */
        $hasBT151orBT95orBT102VatCategoryStandard = false;
        $hasBT118VatCategoryStandard = false;

        /** BR-Z-1 */
        $hasBT151orBT95orBT102VatCategoryZeroRatedGoods = false;
        $countBT118VatCategoryZeroRatedGoods = 0;

        /** BR-E-1 */
        $hasBT151orBT95orBT102VatCategoryExemptFromTax = false;
        $countBT118VatCategoryExemptFromTax = 0;

        /** BR-AE-1 */
        $hasBT151orBT95orBT102VatCategoryReverseCharge = false;
        $countBT118VatCategoryReverseCharge = 0;

        /** BR-IC-1 */
        $hasBT151orBT95orBT102VatCategoryIntraCommunitySupply = false;
        $countBT118VatCategoryIntraCommunitySupply = 0;

        /** BR-G-1 */
        $hasBT151orBT95orBT102VatCategoryExportOutsideEU = false;
        $countBT118VatCategoryExportOutsideEU = 0;

        /** BR-O-1 */
        $hasBT151orBT95orBT102VatCategoryNotSubjectToVat = false;
        $countBT118VatCategoryNotSubjectToVat = 0;

        /** BR-IG-1 */
        $hasBT151orBT95orBT102VatCategoryCanaryIslands = false;
        $hasBT118VatCategoryCanaryIslands = false;

        /** BR-IP-1 */
        $hasBT151orBT95orBT102VatCategoryCeutaMelilla = false;
        $hasBT118VatCategoryCeutaMelilla = false;

        /** BR-S-2 */
        $hasBT151VatCategoryStandard = false;

        /** BR-Z-2 */
        $hasBT151VatCategoryZeroRatedGoods = false;

        /** BR-E-2 */
        $hasBT151VatCategoryExemptFromTax = false;

        /** BR-AE-2 */
        $hasBT151VatCategoryReverseCharge = false;

        /** BR-IC-2 */
        $hasBT151VatCategoryIntraCommunitySupply = false;

        /** BR-G-2 */
        $hasBT151VatCategoryExportOutsideEU = false;

        /** BR-O-2 */
        $hasBT151VatCategoryNotSubjectToVat = false;

        /** BR-IG-2 */
        $hasBT151VatCategoryCanaryIslands = false;

        /** BR-IP-2 */
        $hasBT151VatCategoryCeutaMelilla = false;

        /** BR-S-3 */
        $hasBT95VatCategoryStandard = false;

        /** BR-Z-3 */
        $hasBT95VatCategoryZeroRatedGoods = false;

        /** BR-E-3 */
        $hasBT95VatCategoryExemptFromTax = false;

        /** BR-AE-3 */
        $hasBT95VatCategoryReverseCharge = false;

        /** BR-IC-3 */
        $hasBT95VatCategoryIntraCommunitySupply = false;

        /** BR-G-3 */
        $hasBT95VatCategoryExportOutsideEU = false;

        /** BR-O-3 */
        $hasBT95VatCategoryNotSubjectToVat = false;

        /** BR-IG-3 */
        $hasBT95VatCategoryCanaryIslands = false;

        /** BR-IP-3 */
        $hasBT95VatCategoryCeutaMelilla = false;

        $this->vatBreakdowns = [];
        $totalVatCategoryTaxAmountVatBreakdowns = new DecimalNumber(0);
        foreach ($vatBreakdowns as $vatBreakdown) {
            if (!$vatBreakdown instanceof VatBreakdown) {
                throw new \TypeError();
            }

            $this->vatBreakdowns[] = $vatBreakdown;

            $totalVatCategoryTaxAmountVatBreakdowns = new DecimalNumber(
                $totalVatCategoryTaxAmountVatBreakdowns
                    ->add($vatBreakdown->getVatCategoryTaxAmount())
            );

            /** BR-S-1 */
            if (!$hasBT118VatCategoryStandard && $vatBreakdown->getVatCategoryCode() === VatCategory::STANDARD_RATE) {
                $hasBT118VatCategoryStandard = true;
            }

            /** BR-Z-1 */
            if ($vatBreakdown->getVatCategoryCode() === VatCategory::ZERO_RATED_GOODS) {
                $countBT118VatCategoryZeroRatedGoods++;
            }

            /** BR-E-1 */
            if ($vatBreakdown->getVatCategoryCode() === VatCategory::EXEMPT_FROM_TAX) {
                $countBT118VatCategoryExemptFromTax++;
            }

            /** BR-AE-1 */
            if ($vatBreakdown->getVatCategoryCode() === VatCategory::VAT_REVERSE_CHARGE) {
                $countBT118VatCategoryReverseCharge++;
            }

            /** BR-IC-1 */
            if (
                $vatBreakdown->getVatCategoryCode()
                === VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES
            ) {
                $countBT118VatCategoryIntraCommunitySupply++;
            }

            /** BR-G-1 */
            if ($vatBreakdown->getVatCategoryCode() === VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED) {
                $countBT118VatCategoryExportOutsideEU++;
            }

            /** BR-O-1 */
            if ($vatBreakdown->getVatCategoryCode() === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                $countBT118VatCategoryNotSubjectToVat++;
            }

            /** BR-IG-1 */
            if (
                !$hasBT118VatCategoryCanaryIslands
                && $vatBreakdown->getVatCategoryCode() === VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX
            ) {
                $hasBT118VatCategoryCanaryIslands = true;
            }

            /** BR-IP-1 */
            if (
                !$hasBT118VatCategoryCeutaMelilla
                && $vatBreakdown->getVatCategoryCode()
                    === VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
            ) {
                $hasBT118VatCategoryCeutaMelilla = true;
            }
        }

        $totalVatCategoryTaxAmountVatBreakdowns = round(
            $totalVatCategoryTaxAmountVatBreakdowns->getValue(),
            Amount::DECIMALS
        );

        if (
            count($this->vatBreakdowns) > 0
            && $totalVatCategoryTaxAmountVatBreakdowns
                !== ($documentTotals->getInvoiceTotalVatAmount()?->getValueRounded() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-14');
        }

        if (empty($this->vatBreakdowns)) {
            throw new \Exception('@todo');
        }

        $this->invoiceLines = [];
        $totalNetAmountInvoiceLines = new DecimalNumber(0);
        foreach ($invoiceLines as $invoiceLine) {
            if (!$invoiceLine instanceof InvoiceLine) {
                throw new \TypeError();
            }

            $this->invoiceLines[] = $invoiceLine;

            $totalNetAmountInvoiceLines = new DecimalNumber(
                $totalNetAmountInvoiceLines->add($invoiceLine->getNetAmount())
            );

            $invoiceLineVatCategoryCode = $invoiceLine->getLineVatInformation()->getInvoicedItemVatCategoryCode();

            if ($invoiceLineVatCategoryCode === VatCategory::STANDARD_RATE) {
                /** BR-S-1 */
                if (!$hasBT151orBT95orBT102VatCategoryStandard) {
                    $hasBT151orBT95orBT102VatCategoryStandard = true;
                }

                /** BR-S-2 */
                if (!$hasBT151VatCategoryStandard) {
                    $hasBT151VatCategoryStandard = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::ZERO_RATED_GOODS) {
                /** BR-Z-1 */
                if (!$hasBT151orBT95orBT102VatCategoryZeroRatedGoods) {
                    $hasBT151orBT95orBT102VatCategoryZeroRatedGoods = true;
                }

                /** BR-Z-2 */
                if (!$hasBT151VatCategoryZeroRatedGoods) {
                    $hasBT151VatCategoryZeroRatedGoods = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::EXEMPT_FROM_TAX) {
                /** BR-E-1 */
                if (!$hasBT151orBT95orBT102VatCategoryExemptFromTax) {
                    $hasBT151orBT95orBT102VatCategoryExemptFromTax = true;
                }

                /** BR-E-2 */
                if (!$hasBT151VatCategoryExemptFromTax) {
                    $hasBT151VatCategoryExemptFromTax = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::VAT_REVERSE_CHARGE) {
                /** BR-AE-1 */
                if (!$hasBT151orBT95orBT102VatCategoryReverseCharge) {
                    $hasBT151orBT95orBT102VatCategoryReverseCharge = true;
                }

                /** BR-AE-2 */
                if (!$hasBT151VatCategoryReverseCharge) {
                    $hasBT151VatCategoryReverseCharge = true;
                }
            }

            if (
                $invoiceLineVatCategoryCode
                === VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES
            ) {
                /** BR-IC-1 */
                if (!$hasBT151orBT95orBT102VatCategoryIntraCommunitySupply) {
                    $hasBT151orBT95orBT102VatCategoryIntraCommunitySupply = true;
                }

                /** BR-IC-2 */
                if (!$hasBT151VatCategoryIntraCommunitySupply) {
                    $hasBT151VatCategoryIntraCommunitySupply = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED) {
                /** BR-G-1 */
                if (!$hasBT151orBT95orBT102VatCategoryExportOutsideEU) {
                    $hasBT151orBT95orBT102VatCategoryExportOutsideEU = true;
                }

                /** BR-G-2 */
                if (!$hasBT151VatCategoryExportOutsideEU) {
                    $hasBT151VatCategoryExportOutsideEU = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                /** BR-O-1 */
                if (!$hasBT151orBT95orBT102VatCategoryNotSubjectToVat) {
                    $hasBT151orBT95orBT102VatCategoryNotSubjectToVat = true;
                }

                /** BR-O-2 */
                if (!$hasBT151VatCategoryNotSubjectToVat) {
                    $hasBT151VatCategoryNotSubjectToVat = true;
                }
            }

            if ($invoiceLineVatCategoryCode === VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX) {
                /** BR-IG-1 */
                if (!$hasBT151orBT95orBT102VatCategoryCanaryIslands) {
                    $hasBT151orBT95orBT102VatCategoryCanaryIslands = true;
                }

                /** BR-IG-2 */
                if (!$hasBT151VatCategoryCanaryIslands) {
                    $hasBT151VatCategoryCanaryIslands = true;
                }
            }

            if (
                $invoiceLineVatCategoryCode
                === VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
            ) {
                /** BR-IP-1 */
                if (!$hasBT151orBT95orBT102VatCategoryCeutaMelilla) {
                    $hasBT151orBT95orBT102VatCategoryCeutaMelilla = true;
                }

                /** BR-IP-2 */
                if (!$hasBT151VatCategoryCeutaMelilla) {
                    $hasBT151VatCategoryCeutaMelilla = true;
                }
            }
        }

        if (empty($this->invoiceLines)) {
            throw new \Exception('@todo');
        }

        /** BR-[S/Z/E/IG/IP]-2 */
        if (
            (
                $hasBT151VatCategoryStandard
                || $hasBT151VatCategoryZeroRatedGoods
                || $hasBT151VatCategoryExemptFromTax
                || $hasBT151VatCategoryCanaryIslands
                || $hasBT151VatCategoryCeutaMelilla
            )
            && !$seller->getVatIdentifier()
            && !$seller->getTaxRegistrationIdentifier()
            && !$sellerTaxRepresentativeParty?->getVatIdentifier()
        ) {
            throw new \Exception('@todo : BR-[S/Z/E/IG/IP]-2');
        }

        /** BR-AE-2 */
        if (
            $hasBT151VatCategoryReverseCharge
            && (
                (
                    !$seller->getVatIdentifier()
                    && !$seller->getTaxRegistrationIdentifier()
                    && !$sellerTaxRepresentativeParty?->getVatIdentifier()
                )
                || (!$buyer->getLegalRegistrationIdentifier() && !$buyer->getVatIdentifier())
            )
        ) {
            throw new \Exception('@todo : BR-AE-2');
        }

        /** BR-IC-2 */
        if (
            $hasBT151VatCategoryIntraCommunitySupply
            && !(
                ($seller->getVatIdentifier() xor $sellerTaxRepresentativeParty?->getVatIdentifier())
                && $buyer->getVatIdentifier()
            )
        ) {
            throw new \Exception('@todo : BR-IC-2');
        }

        /** BR-G-2 */
        if (
            $hasBT151VatCategoryExportOutsideEU
            && !($seller->getVatIdentifier() xor $sellerTaxRepresentativeParty?->getVatIdentifier())
        ) {
            throw new \Exception('@todo : BR-G-2');
        }

        /** BR-O-2 */
        if (
            $hasBT151VatCategoryNotSubjectToVat
            && $seller->getVatIdentifier()
            && $sellerTaxRepresentativeParty?->getVatIdentifier()
            && $buyer->getVatIdentifier()
        ) {
            throw new \Exception('@todo : BR-O-2');
        }


        if ($documentTotals->getSumOfInvoiceLineNetAmount()->getValueRounded() !== $totalNetAmountInvoiceLines->getValueRounded()) {
            throw new \Exception('@todo : BR-CO-10');
        }

        $totalBT131_minus_BT107 = $totalNetAmountInvoiceLines->subtract($documentTotals->getSumOfAllowancesOnDocumentLevel() ?? new Amount(0.00));

        $documentTotalsSumOfChargesOnDocumentLevel = $documentTotals->getSumOfChargesOnDocumentLevel() ?? new Amount(0.00);
        $totalBT131_BT107_plus_BT108 = (new DecimalNumber($totalBT131_minus_BT107))
            ->add($documentTotalsSumOfChargesOnDocumentLevel);

        if ($documentTotals->getInvoiceTotalAmountWithoutVat()->getValueRounded() !== $totalBT131_BT107_plus_BT108) {
            throw new \Exception('@todo : BR-CO-13');
        }

        if (
            $vatAccountingCurrencyCode instanceof CurrencyCode
            xor is_float($documentTotals->getInvoiceTotalVatAmountInAccountingCurrency()?->getValueRounded())
        ) {
            throw new \Exception('@todo');
        }

        if (
            $valueAddedTaxPointDate instanceof \DateTimeInterface
            && $valueAddedTaxPointDateCode instanceof DateCode2005
        ) {
            throw new \Exception('@todo : BR-CO-3');
        }

        if ($documentTotals->getAmountDueForPayment() > 0 && null === $paymentDueDate && empty($paymentTerms)) {
            throw new \Exception('@todo : BR-CO-25');
        }

        $totalAmountDocumentLevelAllowances = new DecimalNumber(0);
        $this->documentLevelAllowances = [];
        foreach ($documentLevelAllowances as $documentLevelAllowance) {
            if ($documentLevelAllowance instanceof DocumentLevelAllowance) {
                $this->documentLevelAllowances[] = $documentLevelAllowance;

                $totalAmountDocumentLevelAllowances = new DecimalNumber(
                    $totalAmountDocumentLevelAllowances->add($documentLevelAllowance->getAmount())
                );

                $documentLevelAllowanceVatCategoryCode = $documentLevelAllowance->getVatCategoryCode();

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::STANDARD_RATE) {
                    /** BR-S-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryStandard) {
                        $hasBT151orBT95orBT102VatCategoryStandard = true;
                    }

                    /** BR-S-3 */
                    if (!$hasBT95VatCategoryStandard) {
                        $hasBT95VatCategoryStandard = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::ZERO_RATED_GOODS) {
                    /** BR-Z-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryZeroRatedGoods) {
                        $hasBT151orBT95orBT102VatCategoryZeroRatedGoods = true;
                    }

                    /** BR-Z-3 */
                    if (!$hasBT95VatCategoryZeroRatedGoods) {
                        $hasBT95VatCategoryZeroRatedGoods = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::EXEMPT_FROM_TAX) {
                    /** BR-E-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryExemptFromTax) {
                        $hasBT151orBT95orBT102VatCategoryExemptFromTax = true;
                    }

                    /** BR-E-3 */
                    if (!$hasBT95VatCategoryExemptFromTax) {
                        $hasBT95VatCategoryExemptFromTax = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::VAT_REVERSE_CHARGE) {
                    /** BR-AE-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryReverseCharge) {
                        $hasBT151orBT95orBT102VatCategoryReverseCharge = true;
                    }

                    /** BR-AE-3 */
                    if (!$hasBT95VatCategoryReverseCharge) {
                        $hasBT95VatCategoryReverseCharge = true;
                    }
                }

                if (
                    $documentLevelAllowanceVatCategoryCode
                    === VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES
                ) {
                    /** BR-IC-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryIntraCommunitySupply) {
                        $hasBT151orBT95orBT102VatCategoryIntraCommunitySupply = true;
                    }

                    /** BR-IC-3 */
                    if (!$hasBT95VatCategoryIntraCommunitySupply) {
                        $hasBT95VatCategoryIntraCommunitySupply = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED) {
                    /** BR-G-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryExportOutsideEU) {
                        $hasBT151orBT95orBT102VatCategoryExportOutsideEU = true;
                    }

                    /** BR-G-3 */
                    if (!$hasBT95VatCategoryExportOutsideEU) {
                        $hasBT95VatCategoryExportOutsideEU = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                    /** BR-O-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryNotSubjectToVat) {
                        $hasBT151orBT95orBT102VatCategoryNotSubjectToVat = true;
                    }

                    /** BR-O-3 */
                    if (!$hasBT95VatCategoryNotSubjectToVat) {
                        $hasBT95VatCategoryNotSubjectToVat = true;
                    }
                }

                if ($documentLevelAllowanceVatCategoryCode === VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX) {
                    /** BR-IG-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryCanaryIslands) {
                        $hasBT151orBT95orBT102VatCategoryCanaryIslands = true;
                    }

                    /** BR-IG-3 */
                    if (!$hasBT95VatCategoryCanaryIslands) {
                        $hasBT95VatCategoryCanaryIslands = true;
                    }
                }

                if (
                    $documentLevelAllowanceVatCategoryCode
                    === VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
                ) {
                    /** BR-IP-1 */
                    if (!$hasBT151orBT95orBT102VatCategoryCeutaMelilla) {
                        $hasBT151orBT95orBT102VatCategoryCeutaMelilla = true;
                    }

                    /** BR-IP-3 */
                    if (!$hasBT95VatCategoryCeutaMelilla) {
                        $hasBT95VatCategoryCeutaMelilla = true;
                    }
                }
            }
        }

        /** BR-[S/Z/E/IG/IP]-3 */
        if (
            (
                $hasBT95VatCategoryStandard
                || $hasBT95VatCategoryZeroRatedGoods
                || $hasBT95VatCategoryExemptFromTax
                || $hasBT95VatCategoryCanaryIslands
                || $hasBT95VatCategoryCeutaMelilla
            )
            && !$seller->getVatIdentifier()
            && !$seller->getTaxRegistrationIdentifier()
            && !$sellerTaxRepresentativeParty?->getVatIdentifier()
        ) {
            throw new \Exception('@todo : BR-[S/Z/E/IG/IP]-3');
        }

        /** BR-AE-3 */
        if (
            $hasBT95VatCategoryReverseCharge
            && (
                (
                    !$seller->getVatIdentifier()
                    && !$seller->getTaxRegistrationIdentifier()
                    && !$sellerTaxRepresentativeParty?->getVatIdentifier()
                )
                || (!$buyer->getLegalRegistrationIdentifier() && !$buyer->getVatIdentifier())
            )
        ) {
            throw new \Exception('@todo : BR-AE-3');
        }

        /** BR-IC-3 */
        if (
            $hasBT95VatCategoryIntraCommunitySupply
            && !(
                ($seller->getVatIdentifier() xor $sellerTaxRepresentativeParty?->getVatIdentifier())
                && $buyer->getVatIdentifier()
            )
        ) {
            throw new \Exception('@todo : BR-IC-3');
        }

        /** BR-G-3 */
        if (
            $hasBT95VatCategoryExportOutsideEU
            && !($seller->getVatIdentifier() xor $sellerTaxRepresentativeParty?->getVatIdentifier())
        ) {
            throw new \Exception('@todo : BR-G-3');
        }

        /** BR-O-3 */
        if (
            $hasBT95VatCategoryNotSubjectToVat
            && $seller->getVatIdentifier()
            && $sellerTaxRepresentativeParty?->getVatIdentifier()
            && $buyer->getVatIdentifier()
        ) {
            throw new \Exception('@todo : BR-O-3');
        }

        $totalAmountDocumentLevelAllowances = round($totalAmountDocumentLevelAllowances->getValue(), Amount::DECIMALS);
        if (
            count($this->documentLevelAllowances) > 0
            && $totalAmountDocumentLevelAllowances
                !== ($documentTotals->getSumOfAllowancesOnDocumentLevel()?->getValueRounded() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-11');
        }

        $totalDocumentLevelCharges = new DecimalNumber(0);
        $this->documentLevelCharges = [];
        foreach ($documentLevelCharges as $documentLevelCharge) {
            if ($documentLevelCharge instanceof DocumentLevelCharge) {
                $this->documentLevelCharges[] = $documentLevelCharge;

                $totalDocumentLevelCharges = new DecimalNumber(
                    $totalDocumentLevelCharges->add($documentLevelCharge->getAmount())
                );

                /** BR-S-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryStandard
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::STANDARD_RATE
                ) {
                    $hasBT151orBT95orBT102VatCategoryStandard = true;
                }

                /** BR-Z-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryZeroRatedGoods
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::ZERO_RATED_GOODS
                ) {
                    $hasBT151orBT95orBT102VatCategoryZeroRatedGoods = true;
                }

                /** BR-E-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryExemptFromTax
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::EXEMPT_FROM_TAX
                ) {
                    $hasBT151orBT95orBT102VatCategoryExemptFromTax = true;
                }

                /** BR-AE-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryReverseCharge
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::VAT_REVERSE_CHARGE
                ) {
                    $hasBT151orBT95orBT102VatCategoryReverseCharge = true;
                }

                /** BR-IC-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryIntraCommunitySupply
                    && $documentLevelCharge->getVatCategoryCode()
                        === VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES
                ) {
                    $hasBT151orBT95orBT102VatCategoryIntraCommunitySupply = true;
                }

                /** BR-G-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryExportOutsideEU
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED
                ) {
                    $hasBT151orBT95orBT102VatCategoryExportOutsideEU = true;
                }

                /** BR-O-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryNotSubjectToVat
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX
                ) {
                    $hasBT151orBT95orBT102VatCategoryNotSubjectToVat = true;
                }

                /** BR-IG-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryCanaryIslands
                    && $documentLevelCharge->getVatCategoryCode() === VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX
                ) {
                    $hasBT151orBT95orBT102VatCategoryCanaryIslands = true;
                }

                /** BR-IP-1 */
                if (
                    !$hasBT151orBT95orBT102VatCategoryCeutaMelilla
                    && $documentLevelCharge->getVatCategoryCode()
                        === VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
                ) {
                    $hasBT151orBT95orBT102VatCategoryCeutaMelilla = true;
                }
            }
        }

        $totalDocumentLevelCharges = round($totalDocumentLevelCharges->getValue(), Amount::DECIMALS);
        if (
            count($this->documentLevelCharges) > 0
            && $totalDocumentLevelCharges
                !== ($documentTotals->getSumOfChargesOnDocumentLevel()?->getValueRounded() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-12');
        }

        if ($hasBT151orBT95orBT102VatCategoryStandard && !$hasBT118VatCategoryStandard) {
            throw new \Exception('@todo : BR-S-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryZeroRatedGoods && $countBT118VatCategoryZeroRatedGoods !== 1) {
            throw new \Exception('@todo : BR-Z-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryExemptFromTax && $countBT118VatCategoryExemptFromTax !== 1) {
            throw new \Exception('@todo : BR-E-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryReverseCharge && $countBT118VatCategoryReverseCharge !== 1) {
            throw new \Exception('@todo : BR-AE-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryIntraCommunitySupply && $countBT118VatCategoryIntraCommunitySupply !== 1) {
            throw new \Exception('@todo : BR-IC-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryExportOutsideEU && $countBT118VatCategoryExportOutsideEU !== 1) {
            throw new \Exception('@todo : BR-G-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryNotSubjectToVat && $countBT118VatCategoryNotSubjectToVat !== 1) {
            throw new \Exception('@todo : BR-O-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryCanaryIslands && !$hasBT118VatCategoryCanaryIslands) {
            throw new \Exception('@todo : BR-IG-1');
        }

        if ($hasBT151orBT95orBT102VatCategoryCeutaMelilla && !$hasBT118VatCategoryCeutaMelilla) {
            throw new \Exception('@todo : BR-IP-1');
        }

        $this->number = $number;
        $this->issueDate = $issueDate;
        $this->typeCode = $typeCode;
        $this->invoiceNote = [];
        $this->currencyCode = $currencyCode;
        $this->vatAccountingCurrencyCode = $vatAccountingCurrencyCode;
        $this->valueAddedTaxPointDate = $valueAddedTaxPointDate;
        $this->valueAddedTaxPointDateCode = $valueAddedTaxPointDateCode;
        $this->processControl = $processControl;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->documentTotals = $documentTotals;
        $this->paymentDueDate = $paymentDueDate;
        $this->paymentTerms = $paymentTerms;
        $this->deliveryInformation = $deliveryInformation;

        $this->buyerReference = null;
        $this->projectReference = null;
        $this->contractReference = null;
        $this->purchaseOrderReference = null;
        $this->salesOrderReference = null;
        $this->receivingAdviceReference = null;
        $this->despatchAdviceReference = null;
        $this->tenderOrLotReference = null;
        $this->objectIdentifier = null;
        $this->buyerAccountingReference = null;
        $this->precedingInvoices = [];
        $this->payee = null;
        $this->paymentInstructions = null;
        $this->additionalSupportingDocuments = [];
        $this->sellerTaxRepresentativeParty = $sellerTaxRepresentativeParty;

        $this->checkChargesVatCategoryCodeConsistencyWithPartyIdentifiers();

        $this->checkNotSubjectToVatBreakdown();

        foreach ($this->vatBreakdowns as $vatBreakdown) {
            $this->checkVatBreakdownTaxableAmountCoherence($vatBreakdown);
            $this->checkNeedsDeliveryInformation($vatBreakdown);
        }
    }

    public function getNumber(): InvoiceIdentifier
    {
        return $this->number;
    }

    public function getIssueDate(): \DateTimeInterface
    {
        return $this->issueDate;
    }

    public function getTypeCode(): InvoiceTypeCode
    {
        return $this->typeCode;
    }

    public function getCurrencyCode(): CurrencyCode
    {
        return $this->currencyCode;
    }

    public function getVatAccountingCurrencyCode(): ?CurrencyCode
    {
        return $this->vatAccountingCurrencyCode;
    }

    public function getValueAddedTaxPointDate(): ?\DateTimeInterface
    {
        return $this->valueAddedTaxPointDate;
    }

    public function getValueAddedTaxPointDateCode(): ?DateCode2005
    {
        return $this->valueAddedTaxPointDateCode;
    }

    public function getPaymentDueDate(): ?\DateTimeInterface
    {
        return $this->paymentDueDate;
    }

    public function getBuyerReference(): ?string
    {
        return $this->buyerReference;
    }

    public function getProjectReference(): ?ProjectReference
    {
        return $this->projectReference;
    }

    public function setProjectReference(?ProjectReference $projectReference): self
    {
        $this->projectReference = $projectReference;

        return $this;
    }

    public function getContractReference(): ?ContractReference
    {
        return $this->contractReference;
    }

    public function setContractReference(?ContractReference $contractReference): self
    {
        $this->contractReference = $contractReference;

        return $this;
    }

    public function getPurchaseOrderReference(): ?PurchaseOrderReference
    {
        return $this->purchaseOrderReference;
    }

    public function setPurchaseOrderReference(?PurchaseOrderReference $purchaseOrderReference): self
    {
        $this->purchaseOrderReference = $purchaseOrderReference;

        return $this;
    }

    public function getSalesOrderReference(): ?SalesOrderReference
    {
        return $this->salesOrderReference;
    }

    public function setSalesOrderReference(?SalesOrderReference $salesOrderReference): self
    {
        $this->salesOrderReference = $salesOrderReference;

        return $this;
    }

    public function getReceivingAdviceReference(): ?ReceivingAdviceReference
    {
        return $this->receivingAdviceReference;
    }

    public function setReceivingAdviceReference(?ReceivingAdviceReference $receivingAdviceReference): self
    {
        $this->receivingAdviceReference = $receivingAdviceReference;

        return $this;
    }

    public function getDespatchAdviceReference(): ?DespatchAdviceReference
    {
        return $this->despatchAdviceReference;
    }

    public function setDespatchAdviceReference(?DespatchAdviceReference $despatchAdviceReference): self
    {
        $this->despatchAdviceReference = $despatchAdviceReference;

        return $this;
    }

    public function getTenderOrLotReference(): ?TenderOrLotReference
    {
        return $this->tenderOrLotReference;
    }

    public function setTenderOrLotReference(?TenderOrLotReference $tenderOrLotReference): self
    {
        $this->tenderOrLotReference = $tenderOrLotReference;

        return $this;
    }

    public function getObjectIdentifier(): ?ObjectIdentifier
    {
        return $this->objectIdentifier;
    }

    public function setObjectIdentifier(?ObjectIdentifier $objectIdentifier): self
    {
        $this->objectIdentifier = $objectIdentifier;

        return $this;
    }

    public function getBuyerAccountingReference(): ?string
    {
        return $this->buyerAccountingReference;
    }

    public function setBuyerAccountingReference(?string $buyerAccountingReference): self
    {
        $this->buyerAccountingReference = $buyerAccountingReference;

        return $this;
    }

    public function getPaymentTerms(): ?string
    {
        return $this->paymentTerms;
    }

    /**
     * @return array<int, InvoiceNote>
     */
    public function getInvoiceNote(): array
    {
        return $this->invoiceNote;
    }

    public function getProcessControl(): ProcessControl
    {
        return $this->processControl;
    }

    /**
     * @return array<int, PrecedingInvoice>
     */
    public function getPrecedingInvoices(): array
    {
        return $this->precedingInvoices;
    }

    /**
     * @param array<int, PrecedingInvoice> $precedingInvoices
     */
    public function setPrecedingInvoices(array $precedingInvoices): self
    {
        $this->precedingInvoices = $precedingInvoices;

        return $this;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function getBuyer(): Buyer
    {
        return $this->buyer;
    }

    public function getPayee(): ?Payee
    {
        return $this->payee;
    }

    public function setPayee(?Payee $payee): self
    {
        $this->payee = $payee;

        return $this;
    }

    public function getSellerTaxRepresentativeParty(): ?SellerTaxRepresentativeParty
    {
        return $this->sellerTaxRepresentativeParty;
    }

    public function getDeliveryInformation(): ?DeliveryInformation
    {
        return $this->deliveryInformation;
    }

    public function getPaymentInstructions(): ?PaymentInstructions
    {
        return $this->paymentInstructions;
    }

    public function setPaymentInstructions(?PaymentInstructions $paymentInstructions): self
    {
        $this->paymentInstructions = $paymentInstructions;

        return $this;
    }

    /**
     * @return array<int, DocumentLevelAllowance>
     */
    public function getDocumentLevelAllowances(): array
    {
        return $this->documentLevelAllowances;
    }

    /**
     * @return array<int, DocumentLevelCharge>
     */
    public function getDocumentLevelCharges(): array
    {
        return $this->documentLevelCharges;
    }

    public function getDocumentTotals(): DocumentTotals
    {
        return $this->documentTotals;
    }

    /**
     * @return array<int, VatBreakdown>
     */
    public function getVatBreakdowns(): array
    {
        return $this->vatBreakdowns;
    }

    /**
     * @return array<int, AdditionalSupportingDocument>
     */
    public function getAdditionalSupportingDocuments(): array
    {
        return $this->additionalSupportingDocuments;
    }

    /**
     * @return array<int, InvoiceLine>
     */
    public function getInvoiceLines(): array
    {
        return $this->invoiceLines;
    }

    public function setBuyerReference(?string $buyerReference): self
    {
        $this->buyerReference = $buyerReference;

        return $this;
    }

    public function addIncludedNote(InvoiceNote ...$notes): self
    {
        foreach ($notes as $note) {
            $this->invoiceNote[] = $note;
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    private function checkVatBreakdownTaxableAmountCoherence(VatBreakdown $vatBreakdown): void
    {
        $vatCategoryCode = $vatBreakdown->getVatCategoryCode();
        $positiveRateCategories = [
            VatCategory::STANDARD_RATE,
            VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX,
            VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA
        ];

        $vatRate = new Percentage(0);

        if (in_array($vatCategoryCode, $positiveRateCategories)) {
            $vatRate = $vatBreakdown->getVatCategoryRate();
        }

        if ($vatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
            $vatRate = null;
        }

        $invoiceLinesNetSum = $this->getInvoiceLinesNetSumPerVatCategory($vatCategoryCode, $vatRate);
        $chargesSum = $this->getDocumentLevelChargesSumPerVatCategory($vatCategoryCode, $vatRate);
        $allowancesSum = $this->getDocumentLevelAllowancesSumPerVatCategory($vatCategoryCode, $vatRate);

        $computedSum = new Amount((new DecimalNumber($invoiceLinesNetSum->add($chargesSum)))->subtract($allowancesSum));

        if ($computedSum->getValueRounded() !== $vatBreakdown->getVatCategoryTaxableAmount()->getValueRounded()) {
            throw new \Exception('@todo BR-genericVAT-8');
        }
    }

    /**
     * @throws \Exception
     */
    private function getInvoiceLinesNetSumPerVatCategory(
        VatCategory $vatCategoryCode,
        ?Percentage $vatRate = null
    ): DecimalNumber {
        $sum = new DecimalNumber(0);

        foreach ($this->invoiceLines as $invoiceLine) {
            $vatInformation = $invoiceLine->getLineVatInformation();

            if (
                $vatInformation->getInvoicedItemVatCategoryCode() === $vatCategoryCode
                && $vatInformation->getInvoicedItemVatRate()?->getValueRounded() === $vatRate?->getValueRounded()
            ) {
                $sum = new DecimalNumber($sum->add($invoiceLine->getNetAmount()));
            }
        }

        return $sum;
    }

    /**
     * @throws \Exception
     */
    private function getDocumentLevelChargesSumPerVatCategory(
        VatCategory $vatCategoryCode,
        ?Percentage $vatRate = null
    ): DecimalNumber {
        $sum = new DecimalNumber(0);

        foreach ($this->documentLevelCharges as $charge) {
            if (
                $charge->getVatCategoryCode() === $vatCategoryCode
                && $charge->getVatRate()?->getValueRounded() === $vatRate?->getValueRounded()
            ) {
                $sum = new DecimalNumber($sum->add($charge->getAmount()));
            }
        }

        return $sum;
    }

    /**
     * @throws \Exception
     */
    private function getDocumentLevelAllowancesSumPerVatCategory(
        VatCategory $vatCategoryCode,
        ?Percentage $vatRate = null
    ): DecimalNumber {
        $sum = new DecimalNumber(0);

        foreach ($this->documentLevelAllowances as $allowance) {
            if (
                $allowance->getVatCategoryCode() === $vatCategoryCode
                && $allowance->getVatRate()?->getValueRounded() === $vatRate?->getValueRounded()
            ) {
                $sum = new DecimalNumber($sum->add($allowance->getAmount()));
            }
        }

        return $sum;
    }

    private function checkNeedsDeliveryInformation(VatBreakdown $vatBreakdown): void
    {
        $vatCategory = $vatBreakdown->getVatCategoryCode();

        if ($vatCategory !== VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES) {
            return;
        }

        if (false === $this->deliveryInformation instanceof DeliveryInformation) {
            throw new \Exception('@todo: BR-IC-11');
        }

        if (
            false === $this->deliveryInformation->getActualDeliveryDate() instanceof \DateTimeInterface
            && false === $this->deliveryInformation->getInvoicingPeriod() instanceof InvoicingPeriod
        ) {
            throw new \Exception('@todo: BR-IC-11');
        }

        if (false === $this->deliveryInformation->getDeliverToAddress() instanceof DeliverToAddress) {
            throw new \Exception('@todo: BR-IC-12');
        }
    }

    /**
     * @throws \Exception
     */
    private function checkNotSubjectToVatBreakdown(): void
    {
        $notSubjectToVatBreakdowns = array_filter($this->vatBreakdowns, function (VatBreakdown $vatBreakdown) {
            return $vatBreakdown->getVatCategoryCode() === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX;
        });

        if (0 === count($notSubjectToVatBreakdowns)) {
            return;
        }

        if (1 < count($notSubjectToVatBreakdowns) || 1 < count($this->vatBreakdowns)) {
            throw new \Exception('@todo: BR-0-11');
        }

        $this->checkNotSubjectToVatInvoiceLines();
        $this->checkNotSubjectToVatAllowances();
        $this->checkNotSubjectToVatCharges();
    }

    private function checkNotSubjectToVatInvoiceLines(): void
    {
        foreach ($this->invoiceLines as $invoiceLine) {
            $invoiceLineVatCategoryCode = $invoiceLine->getLineVatInformation()->getInvoicedItemVatCategoryCode();

            if ($invoiceLineVatCategoryCode !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                throw new \Exception('@todo: BR-O-12');
            }
        }
    }

    private function checkNotSubjectToVatAllowances(): void
    {
        foreach ($this->documentLevelAllowances as $documentLevelAllowance) {
            if ($documentLevelAllowance->getVatCategoryCode() !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                throw new \Exception('@todo: BR-O-13');
            }
        }
    }

    private function checkNotSubjectToVatCharges(): void
    {
        foreach ($this->documentLevelCharges as $documentLevelCharge) {
            if ($documentLevelCharge->getVatCategoryCode() !== VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX) {
                throw new \Exception('@todo: BR-O-14');
            }
        }
    }

    private function checkChargesVatCategoryCodeConsistencyWithPartyIdentifiers(): void
    {
        $categoriesRequiringASellerIdentifier = [
            VatCategory::STANDARD_RATE,
            VatCategory::ZERO_RATED_GOODS,
            VatCategory::EXEMPT_FROM_TAX,
            VatCategory::VAT_REVERSE_CHARGE,
            VatCategory::CANARY_ISLANDS_GENERAL_INDIRECT_TAX,
            VatCategory::TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA,
        ];

        $hasSellerVatIdentifier = $this->seller->getVatIdentifier() instanceof VatIdentifier;

        $hasSellerTaxRegistrationIdentifier =
            $this->seller->getTaxRegistrationIdentifier() instanceof TaxRegistrationIdentifier;

        $hasSellerRepresentativeIdentifier =
            $this->sellerTaxRepresentativeParty?->getVatIdentifier() instanceof VatIdentifier;

        $hasBuyerVatIdentifier = $this->buyer->getVatIdentifier() instanceof VatIdentifier;
        $hasBuyerLegalRegistrationIdentifier =
            $this->buyer->getLegalRegistrationIdentifier() instanceof LegalRegistrationIdentifier;

        foreach ($this->documentLevelCharges as $charge) {
            $vatCategoryCode = $charge->getVatCategoryCode();

            if (
                in_array($vatCategoryCode, $categoriesRequiringASellerIdentifier)
                && !(
                    $hasSellerVatIdentifier
                    || $hasSellerTaxRegistrationIdentifier
                    || $hasSellerRepresentativeIdentifier
                )
            ) {
                throw new \Exception('@todo: BR-[S/Z/E/AE/IG/IP]-4');
            }

            if (
                $vatCategoryCode === VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX
                && (
                    $hasSellerVatIdentifier
                    || $hasSellerTaxRegistrationIdentifier
                    || $hasSellerRepresentativeIdentifier
                )
            ) {
                throw new \Exception('@todo: BR-O-4');
            }

            if (
                in_array($vatCategoryCode, [
                    VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES,
                    VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED
                ]) && !($hasSellerVatIdentifier || $hasSellerRepresentativeIdentifier)
            ) {
                throw new \Exception('@todo: BR-[K/G]-4');
            }

            if (
                $vatCategoryCode === VatCategory::VAT_REVERSE_CHARGE
                && !($hasBuyerVatIdentifier || $hasBuyerLegalRegistrationIdentifier)
            ) {
                throw new \Exception('@todo: BR-AE-4');
            }

            if (
                $vatCategoryCode === VatCategory::VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES
                && !$hasBuyerVatIdentifier
            ) {
                throw new \Exception('@todo: BR-K-4');
            }
        }
    }
}
