<?php

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
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\Reference\ContractReference;
use Tiime\EN16931\DataType\Reference\DespatchAdviceReference;
use Tiime\EN16931\DataType\Reference\ProjectReference;
use Tiime\EN16931\DataType\Reference\PurchaseOrderReference;
use Tiime\EN16931\DataType\Reference\ReceivingAdviceReference;
use Tiime\EN16931\DataType\Reference\SalesOrderReference;
use Tiime\EN16931\DataType\Reference\TenderOrLotReference;

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
     * @var array<int, InvoiceNote>
     */
    private array $invoiceNote;

    private ProcessControl $processControl;

    /**
     * @var array<int, PrecedingInvoice>
     */
    private array $precedingInvoices;

    private Seller $seller;

    private Buyer $buyer;

    private ?Payee $payee;

    private ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty;

    private ?DeliveryInformation $deliveryInformation;

    private ?PaymentInstructions $paymentInstructions;

    /**
     * @var array<int, DocumentLevelAllowance>
     */
    private array $documentLevelAllowances;

    /**
     * @var array<int, DocumentLevelCharge>
     */
    private array $documentLevelCharges;

    private DocumentTotals $documentTotals;

    /**
     * @var array<int, VatBreakdown>
     */
    private array $vatBreakdowns;

    /**
     * @var array<int, AdditionalSupportingDocument>
     */
    private array $additionalSupportingDocuments;

    /**
     * @var array<int, InvoiceLine>
     */
    private array $invoiceLines;

    /**
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     */
    public function __construct(
        InvoiceIdentifier $number,
        \DateTimeInterface $issueDate,
        InvoiceTypeCode $typeCode,
        CurrencyCode $currencyCode,
        ProcessControl $processControl,
        Seller $seller,
        Buyer $buyer,
        DocumentTotals $documentTotals,
        array $vatBreakdowns,
        array $invoiceLines
    ) {
        $this->vatBreakdowns = [];
        foreach ($vatBreakdowns as $vatBreakdown) {
            if ($vatBreakdown instanceof VatBreakdown) {
                $this->vatBreakdowns[] = $vatBreakdown;
            }
        }

        if (empty($this->vatBreakdowns)) {
            throw new \Exception('@todo');
        }

        $this->invoiceLines = [];
        foreach ($invoiceLines as $invoiceLine) {
            if ($invoiceLine instanceof InvoiceLine) {
                $this->invoiceLines[] = $invoiceLine;
            }
        }

        if (empty($this->invoiceLines)) {
            throw new \Exception('@todo');
        }

        $this->number = $number;
        $this->issueDate = $issueDate;
        $this->typeCode = $typeCode;
        $this->invoiceNote = [];
        $this->currencyCode = $currencyCode;
        $this->processControl = $processControl;
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->documentTotals = $documentTotals;

        $this->buyerReference = null;
        $this->deliveryInformation = null;
        $this->paymentInstructions = null;
        $this->documentLevelAllowances = [];
        $this->documentLevelCharges = [];
        $this->additionalSupportingDocuments = [];
    }

    public function getXML(): \DOMDocument
    {
        $invoiceXML = new \DOMDocument('1.0', 'UTF-8');

        $crossIndustryInvoice = $invoiceXML->createElement('rsm:CrossIndustryInvoice');
        $crossIndustryInvoice->setAttribute(
            'xmlns:qdt',
            'urn:un:unece:uncefact:data:standard:QualifiedDataType:100'
        );
        $crossIndustryInvoice->setAttribute(
            'xmlns:ram',
            'urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100'
        );
        $crossIndustryInvoice->setAttribute(
            'xmlns:rsm',
            'urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100'
        );
        $crossIndustryInvoice->setAttribute(
            'xmlns:udt',
            'urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100'
        );
        $crossIndustryInvoice->setAttribute(
            'xmlns:xsi',
            'http://www.w3.org/2001/XMLSchema-instance'
        );

        $root = $invoiceXML->appendChild($crossIndustryInvoice);

        $root->appendChild($invoiceXML->createElement('rsm:ExchangedDocumentContext'));
        $root->appendChild($invoiceXML->createElement('rsm:ExchangedDocument'));
        $supplyChainTradeTransaction = $invoiceXML->createElement('rsm:SupplyChainTradeTransaction');
        $root->appendChild($supplyChainTradeTransaction);

        $this->processControl->hydrateXmlDocument($invoiceXML);

        $this->appendToExchangedDocument($invoiceXML, $invoiceXML->createElement('ram:ID', $this->number->value));
        $this->appendToExchangedDocument(
            $invoiceXML,
            $invoiceXML->createElement('ram:TypeCode', $this->typeCode->value)
        );
        $issueDate = $invoiceXML->createElement('ram:IssueDateTime');
        $issueDateString = $invoiceXML->createElement('udt:DateTimeString', $this->issueDate->format('Ymd'));
        $issueDateString->setAttribute('format', '102');
        $issueDate->appendChild($issueDateString);
        $this->appendToExchangedDocument($invoiceXML, $issueDate);

        if (SpecificationIdentifier::MINIMUM !== $this->processControl->getSpecificationIdentifier()->value) {
            foreach ($this->invoiceNote as $note) {
                $note->hydrateXmlDocument($invoiceXML);
            }
        }

        if (SpecificationIdentifier::MINIMUM !== $this->processControl->getSpecificationIdentifier()->value) {
            foreach ($this->invoiceLines as $line) {
                $line->hydrateXmlDocument($invoiceXML);
            }
        }

        $applicableHeaderTradeAgreement = $invoiceXML->createElement('ram:ApplicableHeaderTradeAgreement');

        if (null !== $this->buyerReference) {
            $applicableHeaderTradeAgreement->appendChild(
                $invoiceXML->createElement('ram:BuyerReference', $this->buyerReference)
            );
        }

        $supplyChainTradeTransaction->appendChild($applicableHeaderTradeAgreement);

        $this->seller->hydrateXmlDocument($invoiceXML);
        $this->buyer->hydrateXmlDocument($invoiceXML);

        $supplyChainTradeTransaction->appendChild($invoiceXML->createElement('ram:ApplicableHeaderTradeDelivery'));

        if ($this->deliveryInformation instanceof DeliveryInformation) {
            $this->deliveryInformation->hydrateXmlDocument($invoiceXML);
        }

        $applicableHeaderTradeSettlement = $invoiceXML->createElement('ram:ApplicableHeaderTradeSettlement');
        $applicableHeaderTradeSettlement->appendChild(
            $invoiceXML->createElement('ram:InvoiceCurrencyCode', $this->currencyCode->value)
        );

        if ($this->paymentInstructions instanceof PaymentInstructions) {
            $this->paymentInstructions->hydrateXmlDocument($invoiceXML);
        }

        $supplyChainTradeTransaction->appendChild($applicableHeaderTradeSettlement);

        if (SpecificationIdentifier::MINIMUM !== $this->processControl->getSpecificationIdentifier()->value) {
            foreach ($this->vatBreakdowns as $vatBreakdown) {
                $vatBreakdown->hydrateXmlDocument($invoiceXML);
            }
        }

        $this->documentTotals->hydrateXmlDocument($invoiceXML, $this->processControl->getSpecificationIdentifier());


        return $invoiceXML;
    }

    private function appendToExchangedDocument(\DOMDocument $invoice, \DOMElement $child): void
    {
        $element = $invoice->getElementsByTagName('rsm:ExchangedDocument')->item(0);

        if (false === $element instanceof \DOMElement) {
            throw new \Exception('@todo');
        }

        $element->appendChild($child);
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

    public function setVatAccountingCurrencyCode(?CurrencyCode $vatAccountingCurrencyCode): self
    {
        $this->vatAccountingCurrencyCode = $vatAccountingCurrencyCode;

        return $this;
    }

    public function getValueAddedTaxPointDate(): ?\DateTimeInterface
    {
        return $this->valueAddedTaxPointDate;
    }

    public function setValueAddedTaxPointDate(?\DateTimeInterface $valueAddedTaxPointDate): self
    {
        $this->valueAddedTaxPointDate = $valueAddedTaxPointDate;

        return $this;
    }

    public function getValueAddedTaxPointDateCode(): ?DateCode2005
    {
        return $this->valueAddedTaxPointDateCode;
    }

    public function setValueAddedTexPointDateCode(?DateCode2005 $valueAddedTaxPointDateCode): self
    {
        $this->valueAddedTaxPointDateCode = $valueAddedTaxPointDateCode;

        return $this;
    }

    public function getPaymentDueDate(): ?\DateTimeInterface
    {
        return $this->paymentDueDate;
    }

    public function setPaymentDueDate(?\DateTimeInterface $paymentDueDate): self
    {
        $this->paymentDueDate = $paymentDueDate;

        return $this;
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

    public function setPaymentTerms(?string $paymentTerms): self
    {
        $this->paymentTerms = $paymentTerms;

        return $this;
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
