<?php

declare(strict_types=1);

namespace Tiime\EN16931;

use Tiime\EN16931\BusinessTermsGroup\AdditionalSupportingDocument;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\DeliveryInformation;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoiceNote;
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
use Tiime\EN16931\DataType\Identifier\ObjectIdentifier;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\Reference\ContractReference;
use Tiime\EN16931\DataType\Reference\DespatchAdviceReference;
use Tiime\EN16931\DataType\Reference\ProjectReference;
use Tiime\EN16931\DataType\Reference\PurchaseOrderReference;
use Tiime\EN16931\DataType\Reference\ReceivingAdviceReference;
use Tiime\EN16931\DataType\Reference\SalesOrderReference;
use Tiime\EN16931\DataType\Reference\TenderOrLotReference;
use Tiime\EN16931\SemanticDataType\Amount;
use Tiime\EN16931\SemanticDataType\DecimalNumber;

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
     * A group of business terms providing information on the business process and rules applicable to the Invoice document.
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
     * A group of business terms providing information about where and when the goods and services invoiced are delivered.
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
        array $documentLevelCharges
    ) {
        $this->vatBreakdowns = [];
        $totalVatCategoryTaxAmountVatBreakdowns = new DecimalNumber(0);
        foreach ($vatBreakdowns as $vatBreakdown) {
            if ($vatBreakdown instanceof VatBreakdown) {
                $this->vatBreakdowns[] = $vatBreakdown;

                $totalVatCategoryTaxAmountVatBreakdowns = new DecimalNumber(
                    $totalVatCategoryTaxAmountVatBreakdowns
                        ->add(new DecimalNumber($vatBreakdown->getVatCategoryTaxAmount()))
                );
            }
        }

        $totalVatCategoryTaxAmountVatBreakdowns = round(
            $totalVatCategoryTaxAmountVatBreakdowns->getValue(),
            Amount::DECIMALS
        );

        if (
            count($this->vatBreakdowns) > 0
            && $totalVatCategoryTaxAmountVatBreakdowns
                !== ($documentTotals->getInvoiceTotalVatAmount() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-14');
        }

        if (empty($this->vatBreakdowns)) {
            throw new \Exception('@todo');
        }

        $this->invoiceLines = [];
        $totalNetAmountInvoiceLines = new DecimalNumber(0);
        foreach ($invoiceLines as $invoiceLine) {
            if ($invoiceLine instanceof InvoiceLine) {
                $this->invoiceLines[] = $invoiceLine;

                $totalNetAmountInvoiceLines = new DecimalNumber(
                    $totalNetAmountInvoiceLines->add(new DecimalNumber($invoiceLine->getNetAmount()))
                );
            }
        }

        if (
            count($this->invoiceLines) > 0
            && $documentTotals->getSumOfInvoiceLineNetAmount() !== $totalNetAmountInvoiceLines->getValueRounded()
        ) {
            throw new \Exception('@todo : BR-CO-10');
        }


        $totalBT131_minus_BT107 = $totalNetAmountInvoiceLines->subtract(
            $documentTotals->getSumOfAllowancesOnDocumentLevel() ?
                new Amount($documentTotals->getSumOfAllowancesOnDocumentLevel()) :
                new Amount(0.00)
        );

        $documentTotalsSumOfChargesOnDocumentLevel = $documentTotals->getSumOfChargesOnDocumentLevel() ?
            new Amount($documentTotals->getSumOfChargesOnDocumentLevel()) : new Amount(0.00);
        $totalBT131_BT107_plus_BT108 = (new DecimalNumber($totalBT131_minus_BT107))
            ->add($documentTotalsSumOfChargesOnDocumentLevel);

        if (
            count($this->invoiceLines) > 0
            && $documentTotals->getInvoiceTotalAmountWithoutVat() !== $totalBT131_BT107_plus_BT108
        ) {
            throw new \Exception('@todo : BR-CO-13');
        }

        if (empty($this->invoiceLines)) {
            throw new \Exception('@todo');
        }

        if (
            $vatAccountingCurrencyCode instanceof CurrencyCode
            xor is_float($documentTotals->getInvoiceTotalVatAmountInAccountingCurrency())
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
                    $totalAmountDocumentLevelAllowances->add(new DecimalNumber($documentLevelAllowance->getAmount()))
                );
            }
        }

        $totalAmountDocumentLevelAllowances = round($totalAmountDocumentLevelAllowances->getValue(), Amount::DECIMALS);
        if (
            count($this->documentLevelAllowances) > 0
            && $totalAmountDocumentLevelAllowances
                !== ($documentTotals->getSumOfAllowancesOnDocumentLevel() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-11');
        }

        $totalDocumentLevelCharges = new DecimalNumber(0);
        $this->documentLevelCharges = [];
        foreach ($documentLevelCharges as $documentLevelCharge) {
            if ($documentLevelCharge instanceof DocumentLevelCharge) {
                $this->documentLevelCharges[] = $documentLevelCharge;

                $totalDocumentLevelCharges = new DecimalNumber(
                    $totalDocumentLevelCharges->add(new DecimalNumber($documentLevelCharge->getAmount()))
                );
            }
        }

        $totalDocumentLevelCharges = round($totalDocumentLevelCharges->getValue(), Amount::DECIMALS);
        if (
            count($this->documentLevelCharges) > 0
            && $totalDocumentLevelCharges
                !== ($documentTotals->getSumOfChargesOnDocumentLevel() ?? (new Amount(0.00))->getValueRounded())
        ) {
            throw new \Exception('@todo : BR-CO-12');
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

        $this->buyerReference = null;
        $this->deliveryInformation = null;
        $this->paymentInstructions = null;
        $this->documentLevelCharges = [];
        $this->additionalSupportingDocuments = [];
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

    public function setSellerTaxRepresentativeParty(?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): self
    {
        $this->sellerTaxRepresentativeParty = $sellerTaxRepresentativeParty;

        return $this;
    }

    public function getDeliveryInformation(): ?DeliveryInformation
    {
        return $this->deliveryInformation;
    }

    public function getPaymentInstructions(): ?PaymentInstructions
    {
        return $this->paymentInstructions;
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
}
