<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\AdditionalSupportingDocument;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\CreditTransfer;
use Tiime\EN16931\BusinessTermsGroup\DeliverToAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLineAllowance;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLineCharge;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLinePeriod;
use Tiime\EN16931\BusinessTermsGroup\InvoiceNote;
use Tiime\EN16931\BusinessTermsGroup\ItemAttribute;
use Tiime\EN16931\BusinessTermsGroup\Payee;
use Tiime\EN16931\BusinessTermsGroup\PaymentCardInformation;
use Tiime\EN16931\BusinessTermsGroup\PaymentInstructions;
use Tiime\EN16931\BusinessTermsGroup\PrecedingInvoice;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativePostalAddress;
use Tiime\EN16931\DataType\AllowanceReasonCode;
use Tiime\EN16931\DataType\ChargeReasonCode;
use Tiime\EN16931\DataType\ElectronicAddressScheme;
use Tiime\EN16931\DataType\Identifier\ElectronicAddressIdentifier;
use Tiime\EN16931\DataType\Identifier\ItemClassificationIdentifier;
use Tiime\EN16931\DataType\Identifier\PaymentAccountIdentifier;
use Tiime\EN16931\DataType\Identifier\StandardItemIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\BusinessTermsGroup\InvoicingPeriod;
use Tiime\EN16931\BusinessTermsGroup\ItemInformation;
use Tiime\EN16931\BusinessTermsGroup\LineVatInformation;
use Tiime\EN16931\BusinessTermsGroup\PriceDetails;
use Tiime\EN16931\BusinessTermsGroup\ProcessControl;
use Tiime\EN16931\BusinessTermsGroup\Seller;
use Tiime\EN16931\BusinessTermsGroup\SellerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\CountryAlpha2Code;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\ItemTypeCode;
use Tiime\EN16931\DataType\PaymentMeansCode;
use Tiime\EN16931\DataType\Reference\PrecedingInvoiceReference;
use Tiime\EN16931\DataType\Reference\SupportingDocumentReference;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;

class BusinessRulesIntegrityConstraintsTest extends TestCase
{
    private Invoice $invoice;

    protected function setUp(): void
    {
        $this->invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD, 0.2)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )]
        ))
            ->setBuyerReference("SERVEXEC")
            ->addIncludedNote(
                new InvoiceNote("Lorem Ipsum"),
                new InvoiceNote("Lorem Ipsum"),
            );
    }


    /**
     * @test
     * @testdox BR-1 : An invoice shall have a specification identifier
     */
    public function br1MandatorySpecificationIdentifier(): void
    {
        $specificationIdentifier = $this->invoice->getProcessControl()->getSpecificationIdentifier();

        $this->assertInstanceOf(SpecificationIdentifier::class, $specificationIdentifier);
        $this->assertSame($specificationIdentifier->value, SpecificationIdentifier::BASIC);
    }

    /**
     * @test
     * @testdox BR-2 : An invoice shall have an invoice number
     */
    public function br2MandatoryInvoiceNumber(): void
    {
        $invoiceNumber = $this->invoice->getNumber();

        $this->assertInstanceOf(InvoiceIdentifier::class, $invoiceNumber);
        $this->assertSame($invoiceNumber->value, '34');
    }

    /**
     * @test
     * @testdox BR-3 : An invoice shall have an invoice issue date
     */
    public function br3MandatoryIssueDate(): void
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->invoice->getIssueDate());
    }

    /**
     * @test
     * @testdox BR-4 : An invoice shall have an invoice type code
     */
    public function br4MandatoryTypeCode(): void
    {
        $this->assertInstanceOf(InvoiceTypeCode::class, $this->invoice->getTypeCode());
        $this->assertSame(InvoiceTypeCode::COMMERCIAL_INVOICE, $this->invoice->getTypeCode());
    }

    /**
     * @test
     * @testdox BR-5 : An invoice shall have an invoice currency code
     */
    public function br5MandatoryCurrencyCode(): void
    {
        $this->assertInstanceOf(CurrencyCode::class, $this->invoice->getCurrencyCode());
        $this->assertSame(CurrencyCode::EURO, $this->invoice->getCurrencyCode());
    }

    /**
     * @test
     * @testdox BR-6 : An invoice shall contain the seller name
     */
    public function br6MandatorySellerName(): void
    {
        $this->assertSame('John Doe', $this->invoice->getSeller()->getName());
    }

    /**
     * @test
     * @testdox BR-7 : An invoice shall contain the buyer name
     */
    public function br7MandatoryBuyerName(): void
    {
        $this->assertSame('Richard Roe', $this->invoice->getBuyer()->getName());
    }

    /**
     * @test
     * @testdox BR-8 : An invoice shall contain the seller postal address
     */
    public function br8MandatorySellerPostalAddress(): void
    {
        $this->assertInstanceOf(SellerPostalAddress::class, $this->invoice->getSeller()->getAddress());
    }

    /**
     * @test
     * @testdox BR-9 : The seller postal address shall contain a seller country code
     */
    public function br9MandatorySellerCountryCode(): void
    {
        $sellerPostalAddress = new SellerPostalAddress(CountryAlpha2Code::FRANCE);

        $this->assertInstanceOf(CountryAlpha2Code::class, $sellerPostalAddress->getCountryCode());
        $this->assertSame(CountryAlpha2Code::FRANCE, $sellerPostalAddress->getCountryCode());
    }

    /**
     * @test
     * @testdox BR-10 : An invoice shall contain the buyer postal address
     */
    public function br10MandatoryBuyerPostalAddress(): void
    {
        $this->assertInstanceOf(BuyerPostalAddress::class, $this->invoice->getBuyer()->getAddress());
    }

    /**
     * @test
     * @testdox BR-11 : The buyer postal address shall contain a buyer country code
     */
    public function br11MandatoryBuyerCountryCode(): void
    {
        $buyerPostalAddress = new BuyerPostalAddress(CountryAlpha2Code::FRANCE);

        $this->assertInstanceOf(CountryAlpha2Code::class, $buyerPostalAddress->getCountryCode());
        $this->assertSame(CountryAlpha2Code::FRANCE, $buyerPostalAddress->getCountryCode());
    }

    /**
     * @test
     * @testdox BR-12 : An invoice shall have the sum of invoice line net amount
     */
    public function br12MandatoryInvoiceLineNetAmount(): void
    {
        $this->assertIsFloat($this->invoice->getDocumentTotals()->getSumOfInvoiceLineNetAmount());
        $this->assertEquals(0, $this->invoice->getDocumentTotals()->getSumOfInvoiceLineNetAmount());
    }

    /**
     * @test
     * @testdox BR-13 : An invoice shall have the invoice total amount without VAT
     */
    public function br13MandatoryTotalAmountWithoutVat(): void
    {
        $this->assertIsFloat($this->invoice->getDocumentTotals()->getInvoiceTotalAmountWithoutVat());
        $this->assertEquals(0, $this->invoice->getDocumentTotals()->getInvoiceTotalAmountWithoutVat());
    }

    /**
     * @test
     * @testdox BR-14 : An invoice shall have the invoice total amount with VAT
     */
    public function br14MandatoryTotalAmountWithVat(): void
    {
        $this->assertIsFloat($this->invoice->getDocumentTotals()->getInvoiceTotalAmountWithVat());
        $this->assertEquals(0, $this->invoice->getDocumentTotals()->getInvoiceTotalAmountWithVat());
    }

    /**
     * @test
     * @testdox BR-15 : An invoice shall have the amount due for payment
     */
    public function br15MandatoryAmountDueForPayment(): void
    {
        $this->assertEquals(0, $this->invoice->getDocumentTotals()->getAmountDueForPayment());
    }

    /**
     * @test
     * @testdox BR-16 [case without lines] : An invoice shall have at least one invoice line
     */
    public function br16CaseWithoutLines(): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::MINIMUM)),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD, 0.2)],
            []
        );
    }

    /**
     * @test
     * @testdox BR-16 [case with lines] : An invoice shall have at least one invoice line
     * @dataProvider provideBR16InvoiceLines
     * @param array<int, InvoiceLine> $lines
     */
    public function br16CaseWithLines(array $lines): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::MINIMUM)),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD, 0.2)],
            $lines
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertTrue(count($invoice->getInvoiceLines()) > 0);

        foreach ($invoice->getInvoiceLines() as $line) {
            $this->assertInstanceOf(InvoiceLine::class, $line);
        }
    }

    /**
     * @return array<string, array<string, array<int, InvoiceLine>>>
     */
    public static function provideBR16InvoiceLines(): array
    {
        return [
            'single line' => [
                'lines' => [
                    new InvoiceLine(
                        new InvoiceLineIdentifier('value'),
                        1,
                        UnitOfMeasurement::CENTILITRE_REC20,
                        10,
                        new PriceDetails(10),
                        new LineVatInformation(VatCategory::STANDARD),
                        new ItemInformation('item')
                    )
                ]
            ],
            'multiple lines' => [
                'lines' => [
                    new InvoiceLine(
                        new InvoiceLineIdentifier('value'),
                        1,
                        UnitOfMeasurement::CENTILITRE_REC20,
                        10,
                        new PriceDetails(10),
                        new LineVatInformation(VatCategory::STANDARD),
                        new ItemInformation('item')
                    ),
                    new InvoiceLine(
                        new InvoiceLineIdentifier('value2'),
                        1,
                        UnitOfMeasurement::CENTILITRE_REC20,
                        10,
                        new PriceDetails(10),
                        new LineVatInformation(VatCategory::STANDARD),
                        new ItemInformation('item2')
                    )
                ]
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-17 : The payee name shall be provided in the invoice if the payee is different from the seller
     */
    public function br17MandatoryPayeeNameInPayee(): void
    {
        $this->invoice->setPayee(new Payee('Jane Doe'));

        $this->assertInstanceOf(Payee::class, $this->invoice->getPayee());
        $this->assertSame('Jane Doe', $this->invoice->getPayee()->getName());
    }

    /**
     * @test
     * @testdox BR-18 : The seller tax representative name shall be provided in the invoice if the seller has a seller tax representative party
     */
    public function br18MandatorySellerTaxRepresentativeName(): void
    {
        $sellerTaxRepresentativeParty = new SellerTaxRepresentativeParty(
            'Freddie Hines',
            new VatIdentifier('vatId'),
            new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
        );

        $this->assertSame('Freddie Hines', $sellerTaxRepresentativeParty->getName());
    }

    /**
     * @test
     * @testdox BR-19 : The seller tax representative postal address shall be provided in the invoice if the seller has a seller tax representative party
     */
    public function br19MandatorySellerTaxRepresentativePostalAddress(): void
    {
        $sellerTaxRepresentativeParty = new SellerTaxRepresentativeParty(
            'Freddie Hines',
            new VatIdentifier('vatId'),
            new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
        );

        $this->assertInstanceOf(
            SellerTaxRepresentativePostalAddress::class,
            $sellerTaxRepresentativeParty->getAddress()
        );
    }

    /**
     * @test
     * @testdox BR-20 : The seller tax representative postal address shall contain a tax representative country code if the seller has a seller tax representative party
     */
    public function br20MandatoryCountryCodeInSellerTaxRepresentativePostalAddress(): void
    {
        $address = new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE);

        $this->assertInstanceOf(
            CountryAlpha2Code::class,
            $address->getCountryCode()
        );

        $this->assertSame(
            CountryAlpha2Code::FRANCE,
            $address->getCountryCode()
        );
    }

    /**
     * @test
     * @testdox BR-21 : Each invoice line shall have an invoice line identifier
     */
    public function br21MandatoryInvoiceLineIdentifier(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertInstanceOf(InvoiceLineIdentifier::class, $invoiceLine->getIdentifier());
        $this->assertSame('1', $invoiceLine->getIdentifier()->value);
    }

    /**
     * @test
     * @testdox BR-22 : Each invoice line shall have an invoiced quantity
     */
    public function br22MandatoryInvoicedQuantity(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertIsFloat($invoiceLine->getInvoicedQuantity());
        $this->assertEquals(1, $invoiceLine->getInvoicedQuantity());
    }

    /**
     * @test
     * @testdox BR-23 : An invoice line shall have an invoiced quantity unit of measure code
     */
    public function br23MandatoryInvoicedQuantityUnitOfMeasureCode(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertInstanceOf(UnitOfMeasurement::class, $invoiceLine->getInvoicedQuantityUnitOfMeasureCode());
    }

    /**
     * @test
     * @testdox BR-24 : Each invoice line shall have an invoice line net amount
     */
    public function br24MandatoryInvoiceLineNetAmount(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertEquals(0, $invoiceLine->getNetAmount());
    }

    /**
     * @test
     * @testdox BR-25 : Each invoice line shall contain the item name
     */
    public function br25MandatoryItemName(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertSame('A thing', $invoiceLine->getItemInformation()->getName());
    }

    /**
     * @test
     * @testdox BR-26 : Each invoice line shall contain the item net price
     */
    public function br26MandatoryItemNetPrice(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier("1"),
            1,
            UnitOfMeasurement::BOX_REC21,
            0,
            new PriceDetails(12),
            new LineVatInformation(VatCategory::STANDARD),
            new ItemInformation("A thing"),
        );

        $this->assertEquals(12, $invoiceLine->getPriceDetails()->getItemNetPrice());
    }

    /**
     * @test
     * @testdox BR-27 [case with negative price] : The item net price shall not be negative
     */
    public function br27WithNegativePrice(): void
    {
        $this->expectException(\Exception::class);

        new PriceDetails(-1);
    }

    /**
     * @test
     * @testdox BR-27 [case with positive price] : The item net price shall not be negative
     * @dataProvider provideBR27NetPrices
     */
    public function br27WithPositivePrice(float $netPrice): void
    {
        $priceDetails =  new PriceDetails($netPrice);

        $this->assertSame($netPrice, $priceDetails->getItemNetPrice());
    }

    /**
     * @return array<string, array<int, float>>
     */
    public static function provideBR27NetPrices(): array
    {
        return [
            'strictly positive' => [1.0],
            'zero' => [0.0]
        ];
    }

    /**
     * @test
     * @testdox BR-28 [case with negative price] : The item gross price shall not be negative
     */
    public function br28WithNegativePrice(): void
    {
        $priceDetails = new PriceDetails(1);

        $this->expectException(\Exception::class);

        $priceDetails->setItemGrossPrice(-1);
    }

    /**
     * @test
     * @testdox BR-28 [case with positive price] : The item gross price shall not be negative
     * @dataProvider provideBR28GrossPrices
     */
    public function br28WithPositivePrice(float $price): void
    {
        $priceDetails = new PriceDetails(1);
        $priceDetails->setItemGrossPrice($price);

        $this->assertSame($price, $priceDetails->getItemGrossPrice());
    }

    /**
     * @return array<string, array<int, float>>
     */
    public static function provideBR28GrossPrices(): array
    {
        return [
            'strictly positive' => [1.0],
            'zero' => [0.0]
        ];
    }

    /**
     * @test
     * @testdox BR-29 [case with start date later than end date] : If both invoicing period start date and invoicing period end date are given then the invoicing period end date shall be later or equal to the invoicing period start date
     */
    public function br29CaseWithStartDateLaterThanEndDate(): void
    {
        $this->expectException(\Exception::class);

        new InvoicingPeriod(new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-01'));
    }


    /**
     * @test
     * @testdox BR-29 [case with start date earlier or equal to end date]: If both invoicing period start date and invoicing period end date are given then the invoicing period end date shall be later or equal to the invoicing period start date
     * @dataProvider provideBR29Dates
     */
    public function br29CaseWithStartDateEarlierOrEqualToEndDate(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): void
    {
        $period = new InvoicingPeriod($startDate, $endDate);

        $this->assertEquals($startDate, $period->getStartDate());
        $this->assertEquals($endDate, $period->getEndDate());
    }

    /**
     * @return array<string, array<int, \DateTimeImmutable>>
     */
    public static function provideBR29Dates(): array
    {
        return [
            'start earlier than end' => [new \DateTimeImmutable('2021-01-01'), new \DateTimeImmutable('2021-01-02')],
            'start equal to end' => [new \DateTimeImmutable('2021-01-01'), new \DateTimeImmutable('2021-01-01')]
        ];
    }

    /**
     * @test BR-30
     * @testdox BR-30 [case with start date later than end date] : If both invoice line period start date and invoice line period end date are given then the invoice line period end date shall be later or equal to the invoice line period start date
     */
    public function br30CaseWithStartDateLaterThanEndDate(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLinePeriod(new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-01'));
    }


    /**
     * @test BR-30
     * @testdox BR-30 [case with start date earlier or equal to end date] : If both invoice line period start date and invoice line period end date are given then the invoice line period end date shall be later or equal to the invoice line period start date
     * @dataProvider provideBR30Dates
     */
    public function br30CaseWithStartDateEarlierOrEqualToEndDate(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): void
    {
        $period = new InvoiceLinePeriod($startDate, $endDate);

        $this->assertEquals($startDate, $period->getStartDate());
        $this->assertEquals($endDate, $period->getEndDate());
    }

    /**
     * @return array<string, array<int, \DateTimeImmutable>>
     */
    public static function provideBR30Dates(): array
    {
        return [
            'start earlier than end' => [new \DateTimeImmutable('2021-01-01'), new \DateTimeImmutable('2021-01-02')],
            'start equal to end' => [new \DateTimeImmutable('2021-01-01'), new \DateTimeImmutable('2021-01-01')]
        ];
    }

    /**
     * @test
     * @testdox BR-31 : Each document level allowance shall have a document level allowance amount
     */
    public function br31MandatoryDocumentLevelAllowanceAmount(): void
    {
        $allowance = new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank');

        $this->assertEquals(1, $allowance->getAmount());
    }

    /**
     * @test
     * @testdox BR-32 : Each document level allowance shall have a document level allowance vat category code
     */
    public function br32MandatoryDocumentLevelAllowanceVatCategoryCode(): void
    {
        $allowance = new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank');

        $this->assertSame(VatCategory::STANDARD, $allowance->getVatCategoryCode());
    }

    /**
     * @test
     * @testdox BR-33 [case without reason or code] : Each document level allowance shall have a document level allowance reason or a document level allowance reason code
     */
    public function br33CaseWithoutReasonOrCode(): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::STANDARD);
    }

    /**
     * @test
     * @testdox BR-33 [cases with at least a reason or code] : Each document level allowance shall have a document level allowance reason or a document level allowance reason code
     * @dataProvider provideBR33ReasonAndCodeCombinations
     */
    public function br33CasesWithReasonAndCodeCombinations(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $allowance = new DocumentLevelAllowance(1, VatCategory::STANDARD, $reason, $reasonCode);

        $this->assertSame($reason, $allowance->getReason());
        $this->assertSame($reasonCode, $allowance->getReasonCode());
    }

    /**
     * @return array<string, array{reason: ?string, reasonCode: ?AllowanceReasonCode}>
     */
    public static function provideBR33ReasonAndCodeCombinations(): array
    {
        return [
            'with reason, without code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => null
            ],
            'with reason, with code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => AllowanceReasonCode::STANDARD
            ],
            'without reason, with code' => [
                'reason' => null,
                'reasonCode' => AllowanceReasonCode::STANDARD
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-36 : Each document level charge shall have a document level charge amount
     */
    public function br36MandatoryDocumentLevelChargeAmount(): void
    {
        $charge = new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank');

        $this->assertEquals(1, $charge->getAmount());
    }

    /**
     * @test
     * @testdox BR-37 : Each document level charge shall have a document level charge vat category code
     */
    public function br37MandatoryDocumentLevelChargeVatCategoryCode(): void
    {
        $charge = new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank');

        $this->assertSame(VatCategory::STANDARD, $charge->getVatCategoryCode());
    }

    /**
     * @test
     * @testdox BR-38 [case without reason or code] : Each document level charge shall have a document level charge reason or a document level charge reason code
     */
    public function br38CaseWithoutReasonOrCode(): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::STANDARD);
    }

    /**
     * @test
     * @testdox BR-38 [cases with at least a reason or code] : Each document level charge shall have a document level charge reason or a document level charge reason code
     * @dataProvider provideBR38ReasonAndCodeCombinations
     */
    public function br38CasesWithReasonAndCodeCombinations(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $charge = new DocumentLevelCharge(1, VatCategory::STANDARD, $reason, $reasonCode);

        $this->assertSame($reason, $charge->getReason());
        $this->assertSame($reasonCode, $charge->getReasonCode());
    }

    /**
     * @return array<string, array{reason: ?string, reasonCode: ?ChargeReasonCode}>
     */
    public static function provideBR38ReasonAndCodeCombinations(): array
    {
        return [
            'with reason, without code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => null
            ],
            'with reason, with code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => ChargeReasonCode::TESTING
            ],
            'without reason, with code' => [
                'reason' => null,
                'reasonCode' => ChargeReasonCode::TESTING
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-41 : Each invoice line allowance shall have an invoice line allowance amount
     */
    public function br41MandatoryInvoiceLineAllowanceAmount(): void
    {
        $allowance = new InvoiceLineAllowance(1, 'Hoobastank');

        $this->assertEquals(1, $allowance->getAmount());
    }

    /**
     * @test
     * @testdox BR-42 [case without reason or code] : Each invoice line allowance shall have an invoice line allowance reason or an invoice line allowance reason code
     */
    public function br42CaseWithoutReasonOrCode(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLineAllowance(1);
    }


    /**
     * @test
     * @testdox BR-42 [cases with at least a reason or code] : Each invoice line allowance shall have an invoice line allowance reason or an invoice line allowance reason code
     * @dataProvider provideBR42ReasonAndCodeCombinations
     */
    public function br42CasesWithReasonAndCodeCombinations(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $allowance = new InvoiceLineAllowance(1, $reason, $reasonCode);

        $this->assertSame($reason, $allowance->getReason());
        $this->assertSame($reasonCode, $allowance->getReasonCode());
    }

    /**
     * @return array<string, array{reason: ?string, reasonCode: ?AllowanceReasonCode}>
     */
    public static function provideBR42ReasonAndCodeCombinations(): array
    {
        return [
            'with reason, without code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => null
            ],
            'with reason, with code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => AllowanceReasonCode::STANDARD
            ],
            'without reason, with code' => [
                'reason' => null,
                'reasonCode' => AllowanceReasonCode::STANDARD
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-43 : Each invoice line charge shall have an invoice line charge amount
     */
    public function br43MandatoryInvoiceLineChargeAmount(): void
    {
        $charge = new InvoiceLineCharge(1, 'Hoobastank');

        $this->assertEquals(1, $charge->getAmount());
    }

    /**
     * @test
     * @testdox BR-44 [case without reason or code] : Each invoice line charge shall have an invoice line charge reason or an invoice line charge reason code
     */
    public function br44CaseWithoutReasonOrCode(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLineCharge(1);
    }

    /**
     * @test
     * @testdox BR-44 [case with at least a reason or code] : Each invoice line charge shall have an invoice line charge reason or an invoice line charge reason code
     * @dataProvider provideBR44ReasonAndCodeCombinations
     */
    public function br44CasesWithReasonAndCodeCombinations(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $charge = new InvoiceLineCharge(1, $reason, $reasonCode);

        $this->assertSame($reason, $charge->getReason());
        $this->assertSame($reasonCode, $charge->getReasonCode());
    }

    /**
     * @return array<string, array{reason: ?string, reasonCode: ?ChargeReasonCode}>
     */
    public static function provideBR44ReasonAndCodeCombinations(): array
    {
        return [
            'with reason, without code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => null
            ],
            'with reason, with code' => [
                'reason' => 'Hoobastank',
                'reasonCode' => ChargeReasonCode::TESTING
            ],
            'without reason, with code' => [
                'reason' => null,
                'reasonCode' => ChargeReasonCode::TESTING
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-45 : Each vat breakdown shall have a vat category taxable amount
     */
    public function br45MandatoryVatCategoryTaxableAmount(): void
    {
        $vatBreakdown = new VatBreakdown(5, 1, VatCategory::STANDARD, 0.2);

        $this->assertEquals(5, $vatBreakdown->getVatCategoryTaxableAmount());
    }

    /**
     * @test
     * @testdox BR-46 : Each vat breakdown shall have a vat category tax amount
     */
    public function br46MandatoryVatCategoryTaxAmount(): void
    {
        $vatBreakdown = new VatBreakdown(5, 1, VatCategory::STANDARD, 0.2);

        $this->assertEquals(1, $vatBreakdown->getVatCategoryTaxAmount());
    }

    /**
     * @test
     * @testdox BR-47 : Each vat breakdown shall be defined through a vat category code
     */
    public function br47MandatoryVatCategoryCode(): void
    {
        $vatBreakdown = new VatBreakdown(5, 1, VatCategory::STANDARD, 0.2);

        $this->assertInstanceOf(VatCategory::class, $vatBreakdown->getVatCategoryCode());
        $this->assertSame(VatCategory::STANDARD, $vatBreakdown->getVatCategoryCode());
    }

    /**
     * @test
     * @testdox BR-48 [case with coherent vat category & rate] : Each vat breakdown shall have a vat category rate except if the invoice is not subject to vat
     * @dataProvider provideBR48SuccessfulCases
     */
    public function br48SuccessfulCases(VatCategory $vatCategory, ?float $vatRate): void
    {
        $vatBreakdown = new VatBreakdown(5, 1, $vatCategory, $vatRate);

        $this->assertSame($vatCategory, $vatBreakdown->getVatCategoryCode());
        $this->assertSame($vatRate, $vatBreakdown->getVatCategoryRate());
    }

    /**
     * @return array<string, array{vatCategory: VatCategory, vatRate: ?float}>
     */
    public static function provideBR48SuccessfulCases(): array
    {
        return [
            'subject to vat with rate' => [
                'vatCategory' => VatCategory::STANDARD,
                'vatRate' => 0.2,
            ],
            'not subject to vat without rate' => [
                'vatCategory' => VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX,
                'vatRate' => null,
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-48 [cases with non-coherent vat category & rate] : Each vat breakdown shall have a vat category rate except if the invoice is not subject to vat
     * @dataProvider provideBR48FailingCases
     */
    public function br48FailingCases(VatCategory $vatCategory, ?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(5, 0, $vatCategory, $vatRate);
    }

    /**
     * @return array<string, array{vatCategory: VatCategory, vatRate: ?float}>
     */
    public static function provideBR48FailingCases(): array
    {
        return [
            'subject to vat without rate' => [
                'vatCategory' => VatCategory::STANDARD,
                'vatRate' => null,
            ],
            'not subject to vat with rate' => [
                'vatCategory' => VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX,
                'vatRate' => 0.2,
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-49 : A payment instruction shall specify the payment means type code
     */
    public function br49MandatoryPaymentMeansTypeCode(): void
    {
        $paymentInstructions = new PaymentInstructions(PaymentMeansCode::DEBIT_TRANSFER);

        $this->assertSame(PaymentMeansCode::DEBIT_TRANSFER, $paymentInstructions->getPaymentMeansTypeCode());
    }

    /**
     * @test
     * @testdox BR-50 : A payment account identifier shall be present if credit transfer information is provided in the invoice
     */
    public function br50MandatoryPaymentAccountIdentifier(): void
    {
        $creditTransfer = new CreditTransfer(new PaymentAccountIdentifier('123'));

        $this->assertInstanceOf(PaymentAccountIdentifier::class, $creditTransfer->getPaymentAccountIdentifier());
        $this->assertSame('123', $creditTransfer->getPaymentAccountIdentifier()->value);
    }

    /**
     * @test
     * @testdox BR-51 [with valid numbers] : (Only) The last 4 to 6 digits of the payment card primary account number shall be present if payment card information is provided in the invoice
     * @dataProvider provideBR51ValidNumbers
     */
    public function br51CasesWithValidNumbers(string $validNumber): void
    {
        $invalidNumbers = [
            '',
            'invalid',
            '123',
            '1234567',
            '123*****1234',
        ];

        $paymentCardInformation = new PaymentCardInformation($validNumber);

        $this->assertSame($validNumber, $paymentCardInformation->getPrimaryAccountNumber());
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function provideBR51ValidNumbers(): array
    {
        return [
            ['1234'],
            ['12345'],
            ['123456'],
            ['****1234'],
            ['****12345'],
            ['****123456'],
        ];
    }

    /**
     * @test
     * @testdox BR-51 [with invalid numbers] : (Only) The last 4 to 6 digits of the payment card primary account number shall be present if payment card information is provided in the invoice
     * @dataProvider provideBR51InvalidNumbers
     */
    public function br51CasesWithInvalidNumbers(string $invalidNumber): void
    {
        $this->expectException(\Exception::class);

        new PaymentCardInformation($invalidNumber);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function provideBR51InvalidNumbers(): array
    {
        return [
            [''],
            ['invalid'],
            ['123'],
            ['1234567'],
            ['123*****1234'],
        ];
    }

    /**
     * @test
     * @testdox BR-52 : Each additional supporting document shall contain a supporting document reference
     */
    public function br52MandatorySupportingDocumentReference(): void
    {
        $supportingDocument = new AdditionalSupportingDocument(new SupportingDocumentReference('ref'));

        $this->assertInstanceOf(SupportingDocumentReference::class, $supportingDocument->getReference());
        $this->assertSame('ref', $supportingDocument->getReference()->value);
    }

    /**
     * @test
     * @testdox BR-53 [cases with coherent currency and amount] : If the vat accounting currency code is present then the invoice total vat amount in accounting currency shall be provided
     * @dataProvider provideBR53SuccessfulCases
     */
    public function br53SuccessfulCases(?CurrencyCode $currencyCode, ?float $vatAmount): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            $currencyCode,
            new DocumentTotals(0, 0, 0, 0, $vatAmount),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD, 0.2)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )]
        );

        $this->assertSame($currencyCode, $invoice->getVatAccountingCurrencyCode());
        $this->assertSame($vatAmount, $invoice->getDocumentTotals()->getInvoiceTotalVatAmountInAccountingCurrency());
    }

    /**
     * @return array<string, array{currencyCode: ?CurrencyCode, vatAmount: ?float}>
     */
    public static function provideBR53SuccessfulCases(): array
    {
        return [
            'case with accounting currency code and with total vat amount in accounting currency' => [
                'currencyCode' => CurrencyCode::CANADIAN_DOLLAR,
                'vatAmount' => 1.0,
            ],
            'case without accounting currency code and without total vat amount in accounting currency' => [
                'currencyCode' => null,
                'vatAmount' => null,
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-53 [cases with non-coherent currency and amount] : If the vat accounting currency code is present then the invoice total vat amount in accounting currency shall be provided
     * @dataProvider provideBR53FailingCases
     */
    public function br53FailingCases(?CurrencyCode $currencyCode, ?float $vatAmount): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller('John Doe', new SellerPostalAddress(CountryAlpha2Code::FRANCE)),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            $currencyCode,
            new DocumentTotals(0, 0, 0, 0, $vatAmount),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD, 0.2)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )]
        );
    }


    /**
     * @return array<string, array{currencyCode: ?CurrencyCode, vatAmount: ?float}>
     */
    public static function provideBR53FailingCases(): array
    {
        return [
            'case with accounting currency code and without total vat amount in accounting currency' => [
                'currencyCode' => CurrencyCode::CANADIAN_DOLLAR,
                'vatAmount' => null,
            ],
            'case without accounting currency code and with total vat amount in accounting currency' => [
                'currencyCode' => null,
                'vatAmount' => 1.0,
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-54 : Each item attribute shall contain an item attribute name and an item attribute value
     */
    public function br54MandatoryItemAttributeNameAndValue(): void
    {
        $itemAttribute = new ItemAttribute('name', 'value');

        $this->assertSame('name', $itemAttribute->getName());
        $this->assertSame('value', $itemAttribute->getValue());
    }

    /**
     * @test
     * @testdox BR-55 : Each preceding invoice reference shall contain a preceding invoice reference
     */
    public function br55MandatoryPrecedingInvoiceReference(): void
    {
        $precedingInvoice = new PrecedingInvoice(new PrecedingInvoiceReference('ref'));

        $this->assertInstanceOf(PrecedingInvoiceReference::class, $precedingInvoice->getReference());
        $this->assertSame('ref', $precedingInvoice->getReference()->value);
    }

    /**
     * @test
     * @testdox BR-56 : Each seller tax representative party shall have a seller tax representative vat identifier
     */
    public function br56MandatorySellerTaxRepresentativePartyVatIdentifier(): void
    {
        $sellerTaxRepresentativeParty = new SellerTaxRepresentativeParty(
            'name',
            new VatIdentifier('123'),
            new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
        );

        $this->assertInstanceOf(VatIdentifier::class, $sellerTaxRepresentativeParty->getVatIdentifier());
        $this->assertSame('123', $sellerTaxRepresentativeParty->getVatIdentifier()->value);
    }

    /**
     * @test
     * @testdox BR-57 : Each deliver to address shall contain a deliver to country code
     */
    public function br57MandatoryDeliverToAddressCountryCode(): void
    {
        $deliverToAdress = new DeliverToAddress(CountryAlpha2Code::FRANCE);

        $this->assertSame(CountryAlpha2Code::FRANCE, $deliverToAdress->getCountryCode());
    }

    /**
     * @test BR-61
     * @testdox BR-61 [cases with coherent code and credit transfers] : If the payment means type code means SEPA credit transfer local credit transfer or non SEPA international credit transfer the payment account identifier shall be present
     * @dataProvider provideBR61SuccessfulCases
     *
     * @param array<int, CreditTransfer> $creditTransfers
     */
    public function br61SuccessfulCases(PaymentMeansCode $code, array $creditTransfers): void
    {
        $paymentInstructions = new PaymentInstructions(
            $code,
            $creditTransfers
        );

        $this->assertSame($code, $paymentInstructions->getPaymentMeansTypeCode());

        foreach ($paymentInstructions->getCreditTransfers() as $creditTransfer) {
            $this->assertInstanceOf(PaymentAccountIdentifier::class, $creditTransfer->getPaymentAccountIdentifier());
        }
    }

    /**
     * @return array<string, array{code: PaymentMeansCode, creditTransfers: array<int, CreditTransfer>}>
     */
    public static function provideBR61SuccessfulCases(): array
    {
        return [
            'with code credit transfer and with account identifier' => [
                'code' => PaymentMeansCode::CREDIT_TRANSFER,
                'creditTransfers' => [new CreditTransfer(new PaymentAccountIdentifier('id'))],
            ],
            'with code SEPA credit transfer and with account identifier' => [
                'code' => PaymentMeansCode::SEPA_CREDIT_TRANSFER,
                'creditTransfers' => [new CreditTransfer(new PaymentAccountIdentifier('id'))],
            ],
            'with other code and with account identifier' => [ // shall this case throw an exception ?
                'code' => PaymentMeansCode::ACCEPTED_BILL_OF_EXCHANGE,
                'creditTransfers' => [new CreditTransfer(new PaymentAccountIdentifier('id'))],
            ],
            'with other code and without account identifier' => [
                'code' => PaymentMeansCode::ACCEPTED_BILL_OF_EXCHANGE,
                'creditTransfers' => [],
            ],
        ];
    }

    /**
     * @test BR-61
     * @testdox BR-61 [cases with coherent code and credit transfers] : If the payment means type code means SEPA credit transfer local credit transfer or non SEPA international credit transfer the payment account identifier shall be present
     * @dataProvider provideBR61FailingCases
     *
     * @param array<int, CreditTransfer> $creditTransfers
     */
    public function br61FailingCases(PaymentMeansCode $code, array $creditTransfers): void
    {
        $this->expectException(\Exception::class);

        new PaymentInstructions(
            $code,
            $creditTransfers
        );
    }

    /**
     * @return array<string, array{code: PaymentMeansCode, creditTransfers: array<int, CreditTransfer>}>
     */
    public static function provideBR61FailingCases(): array
    {
        return [
            'with code credit transfer and without account identifier' => [
                'code' => PaymentMeansCode::CREDIT_TRANSFER,
                'creditTransfers' => [],
            ],
            'with code SEPA credit transfer and without account identifier' => [
                'code' => PaymentMeansCode::SEPA_CREDIT_TRANSFER,
                'creditTransfers' => [],
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-62 & BR-63 : The seller and buyer electronic addresses shall have a scheme identifier
     */
    public function br62br63MandatoryElectronicAddressIdentifierScheme(): void
    {
        $electronicAddressIdentifier = new ElectronicAddressIdentifier('value', ElectronicAddressScheme::SIRET_CODE);

        $this->assertSame(ElectronicAddressScheme::SIRET_CODE, $electronicAddressIdentifier->scheme);
    }

    /**
     * @test
     * @testdox BR-64 : The item standard identifier shall have a scheme identifier
     */
    public function br64MandatoryStandardItemIdentifierScheme(): void
    {
        $standardItemIdentifier = new StandardItemIdentifier('value', InternationalCodeDesignator::COMMON_LANGUAGE);

        $this->assertSame(InternationalCodeDesignator::COMMON_LANGUAGE, $standardItemIdentifier->scheme);
    }

    /**
     * @test
     * @testdox BR-65 : The item classification identifier shall have a scheme identifier
     */
    public function br65MandatoryItemClassificationIdentifierScheme(): void
    {
        $itemClassificationIdentifier = new ItemClassificationIdentifier('value', ItemTypeCode::BUYER_ITEM_NUMBER, 'v1');

        $this->assertSame(ItemTypeCode::BUYER_ITEM_NUMBER, $itemClassificationIdentifier->scheme);
    }
}
