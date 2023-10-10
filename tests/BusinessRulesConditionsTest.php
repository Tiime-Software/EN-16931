<?php

namespace Tiime\EN16931\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLineAllowance;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLineCharge;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLinePeriod;
use Tiime\EN16931\BusinessTermsGroup\InvoiceNote;
use Tiime\EN16931\BusinessTermsGroup\InvoicingPeriod;
use Tiime\EN16931\BusinessTermsGroup\ItemAttribute;
use Tiime\EN16931\BusinessTermsGroup\ItemInformation;
use Tiime\EN16931\BusinessTermsGroup\LineVatInformation;
use Tiime\EN16931\BusinessTermsGroup\PriceDetails;
use Tiime\EN16931\BusinessTermsGroup\ProcessControl;
use Tiime\EN16931\BusinessTermsGroup\Seller;
use Tiime\EN16931\BusinessTermsGroup\SellerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativePostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\AllowanceReasonCode;
use Tiime\EN16931\DataType\ChargeReasonCode;
use Tiime\EN16931\DataType\CountryAlpha2Code;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\DateCode2005;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\Identifier\TaxRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;
use Tiime\EN16931\SemanticDataType\Amount;

class BusinessRulesConditionsTest extends TestCase
{
    /**
     * @test
     * @testdox BR-CO-3 : Value added tax point date (BT-7) and Value added tax point date code (BT-8) are mutually exclusive.
     */
    public function brCo3_bt7_null_and_bt8_null(): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                12,
                12,
                14.4,
                14.4,
                invoiceTotalVatAmount: 2.4
            ),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                12,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNull($invoice->getVatAccountingCurrencyCode());
        $this->assertNull($invoice->getValueAddedTaxPointDate());
    }

    /**
     * @test
     * @testdox BR-CO-3 : Value added tax point date (BT-7) and Value added tax point date code (BT-8) are mutually exclusive.
     */
    public function brCo3_bt7_is_set_and_bt8_null(): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                12,
                12,
                14.4,
                14.4,
                invoiceTotalVatAmount: 2.4
            ),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                12,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            new \DateTimeImmutable(),
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNotNull($invoice->getValueAddedTaxPointDate());
        $this->assertNull($invoice->getValueAddedTaxPointDateCode());
    }

    /**
     * @test
     * @testdox BR-CO-3 : Value added tax point date (BT-7) and Value added tax point date code (BT-8) are mutually exclusive.
     */
    public function brCo3_bt7_null_and_bt8_is_set(): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                12,
                12,
                14.4,
                14.4,
                invoiceTotalVatAmount: 2.4
            ),
            [new VatBreakdown(12, 2.4, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                12,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            DateCode2005::DELIVERY_DATE,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertNull($invoice->getValueAddedTaxPointDate());
        $this->assertNotNull($invoice->getValueAddedTaxPointDateCode());
    }

    /**
     * @test
     * @testdox BR-CO-3 : Value added tax point date (BT-7) and Value added tax point date code (BT-8) are mutually exclusive.
     */
    public function brCo3_bt7_is_set_and_bt8_is_set(): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            CurrencyCode::CANADIAN_DOLLAR,
            new DocumentTotals(
                0,
                0,
                20,
                20,
                invoiceTotalVatAmount: 20
            ),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            new \DateTimeImmutable(),
            DateCode2005::DELIVERY_DATE,
            new \DateTimeImmutable(),
            null,
            [],
            []
        );
    }

    /**
     * @test
     * @testdox BR-CO-4 : Each Invoice line (BG-25) shall be categorized with an Invoiced item VAT category code (BT-151).
     */
    public function brCo4(): void
    {
        $invoiceLine = new InvoiceLine(
            new InvoiceLineIdentifier('value'),
            1,
            UnitOfMeasurement::CENTILITRE_REC20,
            10,
            new PriceDetails(10),
            new LineVatInformation(VatCategory::STANDARD_RATE, 20),
            new ItemInformation('item')
        );

        $this->assertNotNull($invoiceLine->getLineVatInformation()->getInvoicedItemVatCategoryCode());
        $this->assertInstanceOf(VatCategory::class, $invoiceLine->getLineVatInformation()->getInvoicedItemVatCategoryCode());
    }

    /**
     * @test
     * @testdox BR-CO-5 : Document level allowance reason code (BT-98) and Document level allowance reason (BT-97) shall indicate the same type of allowance.
     */
    public function brCo5(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-6 : Document level charge reason code (BT-105) and Document level charge reason (BT-104) shall indicate the same type of charge.
     */
    public function brCo6(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-7 : Invoice line allowance reason code (BT-140) and Invoice line allowance reason (BT-139) shall indicate the same type of allowance reason.
     */
    public function brCo7(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-8 : Invoice line charge reason code (BT-145) and Invoice line charge reason (BT144) shall indicate the same type of charge reason.
     */
    public function brCo8(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-9 : The Seller VAT identifier (BT-31), the Seller tax representative VAT identifier (BT-63) and the Buyer VAT identifier (BT-48) shall have a prefix in accordance with ISO code ISO 3166-1 alpha-2 by which the country of issue may be identified. Nevertheless, Greece may use the prefix ‘EL’.
     * @dataProvider provideBrCo9Success
     */
    public function brCo9_success(string $value): void
    {
        $vatIdentifier = new VatIdentifier($value);

        $this->assertInstanceOf(VatIdentifier::class, $vatIdentifier);
        $this->assertSame($value, $vatIdentifier->getValue());
    }

    public static function provideBrCo9Success(): \Generator
    {
        yield 'BR-CO-9 Success #1' => [
            'value' => 'IT123456789',
        ];
        yield 'BR-CO-9 Success #2' => [
            'value' => 'EL987654321',
        ];
        yield 'BR-CO-9 Success #3' => [
            'value' => 'NL-967611265',
        ];
    }

    /**
     * @test
     * @testdox BR-CO-9 : The Seller VAT identifier (BT-31), the Seller tax representative VAT identifier (BT-63) and the Buyer VAT identifier (BT-48) shall have a prefix in accordance with ISO code ISO 3166-1 alpha-2 by which the country of issue may be identified. Nevertheless, Greece may use the prefix ‘EL’.
     * @dataProvider provideBrCo9Error
     */
    public function brCo9_error(string $value): void
    {
        $this->expectException(\Exception::class);

        new VatIdentifier($value);
    }

    public static function provideBrCo9Error(): \Generator
    {
        yield 'BR-CO-9 Error #1' => [
            'value' => 'A123456789',
        ];
        yield 'BR-CO-9 Error #2' => [
            'value' => '987654321',
        ];
        yield 'BR-CO-9 Error #3' => [
            'value' => '967611265',
        ];
    }

    /**
     * @test
     * @testdox BR-CO-10 : Sum of Invoice line net amount (BT-106) = ∑ Invoice line net amount (BT-131).
     * @dataProvider provideBrCo10Success
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     */
    public function brCo10_success(DocumentTotals $documentTotals, array $vatBreakdowns, array $invoiceLines): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR989465454')
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $invoiceLinesFromObject = $invoice->getInvoiceLines();
        $invoiceLinesTotal = 0.00;
        foreach ($invoiceLinesFromObject as $invoiceLineFromObject) {
            $invoiceLinesTotal += $invoiceLineFromObject->getNetAmount()->multiply($invoiceLineFromObject->getInvoicedQuantity());
        }

        $this->assertEquals($invoice->getDocumentTotals()->getSumOfInvoiceLineNetAmount()->getValueRounded(), round($invoiceLinesTotal * 100) / 100);
    }

    public static function provideBrCo10Success(): \Generator
    {
        yield 'One invoice line with positive amount' => [
            'documentTotals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100.00,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'One invoice line with negative amount' => [
            'documentTotals' => new DocumentTotals(
                -100,
                -100,
                -120,
                -120,
                invoiceTotalVatAmount: -20
            ),
            'vatBreakdowns' => [new VatBreakdown(-100, -20, VatCategory::STANDARD_RATE, 20.0)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    -100.00,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'One invoice line with amount equal to 0 (float)' => [
            'documentTotals' => new DocumentTotals(
                0,
                0,
                0,
                0
            ),
            'vatBreakdowns' => [new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 20.0)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0.00,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'One invoice line with amount equal to 0 (int)' => [
            'documentTotals' => new DocumentTotals(
                0,
                0,
                0,
                0
            ),
            'vatBreakdowns' => [new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 20.0)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'Two invoice lines with two positives numbers and positive total' => [
            'documentTotals' => new DocumentTotals(
                200,
                200,
                240,
                240,
                invoiceTotalVatAmount: 40
            ),
            'vatBreakdowns' => [new VatBreakdown(200, 40, VatCategory::STANDARD_RATE, 20.0)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    110.00,
                    new PriceDetails(110),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    90.00,
                    new PriceDetails(90),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'Two invoice lines with one positive number / one negative number and positive total' => [
            'documentTotals' => new DocumentTotals(
                20,
                20,
                24,
                24,
                invoiceTotalVatAmount: 4
            ),
            'vatBreakdowns' => [new VatBreakdown(20, 4, VatCategory::STANDARD_RATE, 20)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    110.00,
                    new PriceDetails(110),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    -90.00,
                    new PriceDetails(90),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'Two invoice lines with one positive number / one negative number and negative total' => [
            'documentTotals' => new DocumentTotals(
                -20,
                -20,
                -24,
                -24,
                invoiceTotalVatAmount:-4
            ),
            'vatBreakdowns' => [new VatBreakdown(-20, -4, VatCategory::STANDARD_RATE, 20)],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    -110.00,
                    new PriceDetails(110),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    90.00,
                    new PriceDetails(90),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-10 : Sum of Invoice line net amount (BT-106) = ∑ Invoice line net amount (BT-131).
     * @dataProvider provideBrCo10Error
     * @param array<int, InvoiceLine> $invoiceLines
     */
    public function brCo10_error(DocumentTotals $documentTotals, array $invoiceLines): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        );
    }

    public static function provideBrCo10Error(): \Generator
    {
        yield 'Error with two invoice lines' => [
            'documentTotals' => new DocumentTotals(
                200.01,
                0,
                20,
                20,
                invoiceTotalVatAmount: 20
            ),
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    110.00,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    90.00,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'Error with one invoice lines' => [
            'documentTotals' => new DocumentTotals(
                -91,
                0,
                20,
                20,
                invoiceTotalVatAmount: 20
            ),
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    -90.00,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-11 : Sum of allowances on document level (BT-107) = ∑ Document level allowance amount (BT-92).
     * @dataProvider provideBrCo11Success
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     */
    public function brCo11_success(DocumentTotals $documentTotals, array $vatBreakdowns, array $invoiceLines, array $documentLevelAllowances): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR95645545')
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrCo11Success(): \Generator
    {
        yield 'BR-CO-11 Success #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    100,
                    0,
                    0,
                    0,
                    sumOfAllowancesOnDocumentLevel: 100
                ),
            'vatBreakdowns' => [
                new VatBreakdown(0, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(100, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, reasonCode: AllowanceReasonCode::STANDARD)
            ]
        ];
        yield 'BR-CO-11 Success #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    100,
                    100,
                    100,
                    100,
                    sumOfAllowancesOnDocumentLevel: 0
                ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, reasonCode: AllowanceReasonCode::STANDARD)
            ]
        ];
        yield 'BR-CO-11 Success #3' => [
            'documentTotals' =>
                new DocumentTotals(
                    2000,
                    1000,
                    1200,
                    1200,
                    invoiceTotalVatAmount: 200,
                    sumOfAllowancesOnDocumentLevel: 1000.00
                ),
            'vatBreakdowns' => [
                new VatBreakdown(1000, 200, VatCategory::STANDARD_RATE, 20)
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    2000,
                    new PriceDetails(2000),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD_RATE, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20),
                new DocumentLevelAllowance(900.0, VatCategory::STANDARD_RATE, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20)
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-11 : Sum of allowances on document level (BT-107) = ∑ Document level allowance amount (BT-92).
     * @dataProvider provideBrCo11Error
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     */
    public function brCo11_error(DocumentTotals $documentTotals, array $documentLevelAllowances): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            []
        );
    }

    public static function provideBrCo11Error(): \Generator
    {
        yield 'BR-CO-11 Error #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    20,
                    20,
                    invoiceTotalVatAmount: 20,
                    sumOfAllowancesOnDocumentLevel: 1000.00
                ),
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(0.0, VatCategory::STANDARD_RATE, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20)
            ]
        ];
        yield 'BR-CO-11 Error #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    20,
                    20,
                    invoiceTotalVatAmount: 20,
                    sumOfAllowancesOnDocumentLevel: 1000.00
                ),
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD_RATE, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20),
                new DocumentLevelAllowance(1000.0, VatCategory::STANDARD_RATE, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20)
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-12 : Sum of charges on document level (BT-108) = ∑ Document level charge amount (BT-99).
     * @dataProvider provideBrCo12Success
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     */
    public function brCo12_success(DocumentTotals $documentTotals, array $vatBreakdowns, array $documentLevelCharges): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('AZE')
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                100,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            $documentLevelCharges
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrCo12Success(): \Generator
    {
        yield 'BR-CO-12 Success #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    100,
                    200,
                    220,
                    220,
                    invoiceTotalVatAmount: 20,
                    sumOfChargesOnDocumentLevel: 100
                ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
                new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00),
            ],
            'documentLevelCharges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20)
            ]
        ];
        yield 'BR-CO-12 Success #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    100,
                    100,
                    100,
                    100,
                    invoiceTotalVatAmount: 0,
                    sumOfChargesOnDocumentLevel: 0
                ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
                new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 20.00),
            ],
            'documentLevelCharges' => [
                new DocumentLevelCharge(0, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20),
            ]
        ];
        yield 'BR-CO-12 Success #3' => [
            'documentTotals' =>
                new DocumentTotals(
                    100,
                    160,
                    172,
                    172,
                    invoiceTotalVatAmount: 12,
                    sumOfChargesOnDocumentLevel: 60
                ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
                new VatBreakdown(60, 12, VatCategory::STANDARD_RATE, 20.00),
            ],
            'documentLevelCharges' => [
                new DocumentLevelCharge(10, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20),
                new DocumentLevelCharge(50, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20),
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-12 : Sum of charges on document level (BT-108) = ∑ Document level charge amount (BT-99).
     * @dataProvider provideBrCo12Error
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     */
    public function brCo12_error(DocumentTotals $documentTotals, array $documentLevelCharges): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            $documentLevelCharges
        );
    }

    public static function provideBrCo12Error(): \Generator
    {
        yield 'BR-CO-12 Error #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    20,
                    20,
                    invoiceTotalVatAmount: 20,
                    sumOfChargesOnDocumentLevel: 1000
                ),
            'documentLevelCharges' => [
                new DocumentLevelCharge(0, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20)
            ]
        ];
        yield 'BR-CO-12 Error #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    20,
                    20,
                    invoiceTotalVatAmount: 20,
                    sumOfChargesOnDocumentLevel: 1000
                ),
            'documentLevelCharges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20),
                new DocumentLevelCharge(1000, VatCategory::STANDARD_RATE, reasonCode: ChargeReasonCode::ADVERTISING, vatRate: 20)
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-13 : Invoice total amount without VAT (BT-109) = ∑ Invoice line net amount (BT-131) - Sum of allowances on document level (BT-107) + Sum of charges on document level (BT-108).
     * @dataProvider provideBrCo13Success
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     */
    public function brCo13_success(DocumentTotals $documentTotals, array $vatBreakdowns, array $invoiceLines): void
    {
        // TODO : error cases

        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR95645545')
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrCo13Success(): \Generator
    {
        yield 'BR-CO-13 Success #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    1000,
                    1000,
                    1000,
                    1000
                ),
            'vatBreakdown' => [
                new VatBreakdown(
                    1000,
                    0,
                    VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX,
                    vatExemptionReasonText: 'Hoobastank'
                )
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'BR-CO-13 Success #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    1500,
                    1500,
                    1500,
                    1500
                ),
            'vatBreakdown' => [
                new VatBreakdown(
                    1500,
                    0,
                    VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX,
                    vatExemptionReasonText: 'Hoobastank'
                )
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    500,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ]
        ];
        yield 'BR-CO-13 Success #3' => [
            'documentTotals' =>
                new DocumentTotals(
                    2000,
                    1000,
                    2000,
                    2000,
                    invoiceTotalVatAmount: 1000,
                    sumOfAllowancesOnDocumentLevel: 1000
                ),
            'vatBreakdown' => [
                new VatBreakdown(
                    2000,
                    1000,
                    VatCategory::STANDARD_RATE,
                    vatCategoryRate: 50
                )
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 50.0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 50.0),
                    new ItemInformation("A thing"),
                )
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-14 : Invoice total VAT amount (BT-110) = ∑ VAT category tax amount (BT-117).
     * @dataProvider provideBrCo14Success
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $invoiceLines
     */
    public function brCo14_success(DocumentTotals $documentTotals, array $vatBreakdowns, array $invoiceLines): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrCo14Success(): \Generator
    {
        yield 'BR-CO-14 Success #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    1000,
                    1000,
                    1250,
                    1250,
                    invoiceTotalVatAmount: 250
                ),
            'vatBreakdowns' => [
                new VatBreakdown(1000, 250, VatCategory::STANDARD_RATE, 25)
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 25),
                    new ItemInformation("A thing"),
                )
            ],
        ];
        yield 'BR-CO-14 Success #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    1600,
                    1600,
                    1970,
                    1970,
                    invoiceTotalVatAmount: 370
                ),
            'vatBreakdowns' => [
                new VatBreakdown(1000, 250, VatCategory::STANDARD_RATE, 25),
                new VatBreakdown(600, 120, VatCategory::STANDARD_RATE, 20)
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 25),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    600,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ],
        ];
        yield 'BR-CO-14 Success #3' => [
            'documentTotals' =>
                new DocumentTotals(
                    1000,
                    1000,
                    1250,
                    1250,
                    invoiceTotalVatAmount: 250.0
                ),
            'vatBreakdowns' => [
                new VatBreakdown(1000, 250, VatCategory::STANDARD_RATE, 25),
                new VatBreakdown(0, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(12),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 25),
                    new ItemInformation("A thing"),
                )
            ],
        ];
        yield 'BR-CO-14 Success #4' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    0,
                    0,
                    invoiceTotalVatAmount: 00
                ),
            'vatBreakdowns' => [
                new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 20),
            ],
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                )
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-CO-14 : Invoice total VAT amount (BT-110) = ∑ VAT category tax amount (BT-117).
     * @dataProvider provideBrCo14Error
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brCo14_error(DocumentTotals $documentTotals, array $vatBreakdowns): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $documentTotals,
            $vatBreakdowns,
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        );
    }

    public static function provideBrCo14Error(): \Generator
    {
        yield 'BR-CO-14 Error #1' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    250,
                    250,
                    invoiceTotalVatAmount: 250
                ),
            'vatBreakdowns' => [
                new VatBreakdown(600, 300, VatCategory::STANDARD_RATE, 50)
            ]
        ];
        yield 'BR-CO-14 Error #2' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    300,
                    300,
                    invoiceTotalVatAmount: 300
                ),
            'vatBreakdowns' => [
                new VatBreakdown(1000, 250, VatCategory::STANDARD_RATE, 25),
                new VatBreakdown(600, 150, VatCategory::STANDARD_RATE, 25)
            ]
        ];
        yield 'BR-CO-14 Error #3' => [
            'documentTotals' =>
                new DocumentTotals(
                    0,
                    0,
                    1,
                    1,
                    invoiceTotalVatAmount: 1
                ),
            'vatBreakdowns' => [
                new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 0),
                new VatBreakdown(0, 0, VatCategory::STANDARD_RATE, 0)
            ]
        ];
    }

    /**
     * @test
     * @testdox BR-CO-15 : Invoice total amount with VAT (BT-112) = Invoice total amount without VAT (BT-109) + Invoice total VAT amount (BT-110).
     * @dataProvider provideBrCo15_success
     */
    public function brCo15_success(
        float $invoiceTotalAmountWithoutVat,
        ?float $invoiceTotalVatAmount,
        float $invoiceTotalAmountWithVat,
        float $amountDueForPayment
    ): void
    {
        $documentTotals = new DocumentTotals(
            0,
            $invoiceTotalAmountWithoutVat,
            $invoiceTotalAmountWithVat,
            $amountDueForPayment,
            invoiceTotalVatAmount: $invoiceTotalVatAmount
        );

        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
        $this->assertEquals(
            $documentTotals->getInvoiceTotalAmountWithVat()->getValueRounded(),
            $documentTotals->getInvoiceTotalAmountWithoutVat()
                ->add($documentTotals->getInvoiceTotalVatAmount() ?? new Amount(0.00))
        );
    }

    public static function provideBrCo15_success(): \Generator
    {
        // BT-109, BT-110, BT-112, BT-115
        yield 'Standard calculation' => [
            'invoiceTotalAmountWithoutVat' => 1000,
            'invoiceTotalVatAmount' => 300,
            'invoiceTotalAmountWithVat' => 1300,
            'amountDueForPayment' => 1300
        ];
        yield 'Standard calculation with VAT to null' => [
            'invoiceTotalAmountWithoutVat' => 1300,
            'invoiceTotalVatAmount' => null,
            'invoiceTotalAmountWithVat' => 1300,
            'amountDueForPayment' => 1300
        ];
        yield 'Standard calculation with VAT to 0' => [
            'invoiceTotalAmountWithoutVat' => 1300,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1300,
            'amountDueForPayment' => 1300
        ];
        yield 'Calculation with Invoice Total Amount Without VAT < 0' => [
            'invoiceTotalAmountWithoutVat' => -100,
            'invoiceTotalVatAmount' => 300,
            'invoiceTotalAmountWithVat' => 200,
            'amountDueForPayment' => 200
        ];
        yield 'Calculation with Invoice Total Amount Without VAT < 0 and Invoice Total Amount With VAT = 0' => [
            'invoiceTotalAmountWithoutVat' => -100,
            'invoiceTotalVatAmount' => 100,
            'invoiceTotalAmountWithVat' => 0.0,
            'amountDueForPayment' => 0.0
        ];
        yield 'Calculation with all data = 0' => [
            'invoiceTotalAmountWithoutVat' => 0.00,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 0.0,
            'amountDueForPayment' => 0.0
        ];
    }

    /**
     * @test
     * @testdox BR-CO-15 : Invoice total amount with VAT (BT-112) = Invoice total amount without VAT (BT-109) + Invoice total VAT amount (BT-110).
     * @dataProvider provideBrCo15_error
     */
    public function brCo15_error(float $invoiceTotalAmountWithVat, float $invoiceTotalAmountWithoutVat, ?float $invoiceTotalVatAmount): void
    {
        $this->expectException(\Exception::class);

        new DocumentTotals(
            0,
            $invoiceTotalAmountWithoutVat,
            $invoiceTotalAmountWithVat,
            0,
            invoiceTotalVatAmount: $invoiceTotalVatAmount
        );
    }

    public static function provideBrCo15_error(): \Generator
    {
        yield 'Invoice total amount with VAT != Invoice total amount without VAT + Invoice total VAT amount #1' => [
            1250.00, 1000.00, 0
        ];
        yield 'Invoice total amount with VAT != Invoice total amount without VAT + Invoice total VAT amount #2' => [
            0.01, -100, 100
        ];
        yield 'Invoice total amount with VAT != Invoice total amount without VAT + Invoice total VAT amount #3' => [
            12, 10, null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-16 : Amount due for payment (BT-115) = Invoice total amount with VAT (BT-112) - Paid amount (BT-113) + Rounding amount (BT-114).
     * @dataProvider provideBrCo16_success
     */
    public function brCo16_success(
        float $invoiceTotalAmountWithoutVat,
        ?float $invoiceTotalVatAmount,
        float $invoiceTotalAmountWithVat,
        ?float $paidAmount,
        ?float $roundingAmount,
        float $amountDueForPayment
    ): void
    {
        $documentTotals = new DocumentTotals(
            0,
            $invoiceTotalAmountWithoutVat,
            $invoiceTotalAmountWithVat,
            $amountDueForPayment,
            invoiceTotalVatAmount: $invoiceTotalVatAmount,
            paidAmount: $paidAmount,
            roundingAmount: $roundingAmount
        );

        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
    }

    public static function provideBrCo16_success(): \Generator
    {
        yield 'BR-CO-16 Success #1' => [
            'invoiceTotalAmountWithoutVat' => 1200,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200,
            'paidAmount' => 1000,
            'roundingAmount' => null,
            'amountDueForPayment' => 200.00
        ];
        yield 'BR-CO-16 Success #2' => [
            'invoiceTotalAmountWithoutVat' => 8250.00,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 8250.00,
            'paidAmount' => null,
            'roundingAmount' => null,
            'amountDueForPayment' => 8250.0
        ];
        yield 'BR-CO-16 Success #3' => [
            'invoiceTotalAmountWithoutVat' => 1200.00,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.00,
            'paidAmount' => 0,
            'roundingAmount' => null,
            'amountDueForPayment' => 1200.00
        ];
        yield 'BR-CO-16 Success #4' => [
            'invoiceTotalAmountWithoutVat' => 1200.00,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.00,
            'paidAmount' => 1200,
            'roundingAmount' => null,
            'amountDueForPayment' => 0.0
        ];
        yield 'BR-CO-16 Success #5' => [
            'invoiceTotalAmountWithoutVat' => 1200.78,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.78,
            'paidAmount' => 1000.0,
            'roundingAmount' => 0.22,
            'amountDueForPayment' => 201
        ];
        yield 'BR-CO-16 Success #6' => [
            'invoiceTotalAmountWithoutVat' => 1200.78,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.78,
            'paidAmount' => null,
            'roundingAmount' => 0.22,
            'amountDueForPayment' => 1201
        ];
        yield 'BR-CO-16 Success #7' => [
            'invoiceTotalAmountWithoutVat' => 1200.22,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.22,
            'paidAmount' => null,
            'roundingAmount' => -0.22,
            'amountDueForPayment' => 1200.0
        ];
    }

    /**
     * @test
     * @testdox BR-CO-16 : Amount due for payment (BT-115) = Invoice total amount with VAT (BT-112) - Paid amount (BT-113) + Rounding amount (BT-114).
     * @dataProvider provideBrCo16_error
     */
    public function brCo16_error(
        float $invoiceTotalAmountWithoutVat,
        ?float $invoiceTotalVatAmount,
        float $invoiceTotalAmountWithVat,
        ?float $paidAmount,
        ?float $roundingAmount,
        float $amountDueForPayment
    ): void
    {
        $this->expectException(\Exception::class);

        $documentTotals = new DocumentTotals(
            0,
            $invoiceTotalAmountWithoutVat,
            $invoiceTotalAmountWithVat,
            $amountDueForPayment,
            invoiceTotalVatAmount: $invoiceTotalVatAmount,
            paidAmount: $paidAmount,
            roundingAmount: $roundingAmount
        );

        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
    }

    public static function provideBrCo16_error(): \Generator
    {
        yield 'BR-CO-16 Error #1' => [
            'invoiceTotalAmountWithoutVat' => 1200,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200, // x
            'paidAmount' => 100, // x
            'roundingAmount' => null,
            'amountDueForPayment' => 1200 // x
        ];
        yield 'BR-CO-16 Error #2' => [
            'invoiceTotalAmountWithoutVat' => 1200,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200.78,
            'paidAmount' => 100,
            'roundingAmount' => 0.22,
            'amountDueForPayment' => 1100.78
        ];
        yield 'BR-CO-16 Error #3' => [
            'invoiceTotalAmountWithoutVat' => 1200,
            'invoiceTotalVatAmount' => 0,
            'invoiceTotalAmountWithVat' => 1200,
            'paidAmount' => null,
            'roundingAmount' => null,
            'amountDueForPayment' => 1100
        ];
    }

    /**
     * @test
     * @testdox BR-CO-17 : VAT category tax amount (BT-117) = VAT category taxable amount (BT-116) x (VAT category rate (BT-119) / 100), rounded to two decimals.
     * @dataProvider provideBrCo17_success
     */
    public function brCo17_success(
        float $vatCategoryTaxableAmount,
        float $vatCategoryTaxAmount,
        VatCategory $vatCategoryCode,
        ?float $vatCategoryRate,
        ?string $vatExemptionReasonText = null
    ): void
    {
        $vatBreakdown = new VatBreakdown(
            $vatCategoryTaxableAmount,
            $vatCategoryTaxAmount,
            $vatCategoryCode,
            $vatCategoryRate,
            vatExemptionReasonText: $vatExemptionReasonText
        );

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
        $this->assertSame($vatCategoryTaxableAmount, $vatBreakdown->getVatCategoryTaxableAmount()->getValueRounded());
        $this->assertSame($vatCategoryTaxAmount, $vatBreakdown->getVatCategoryTaxAmount()->getValueRounded());
        $this->assertSame($vatCategoryCode, $vatBreakdown->getVatCategoryCode());
        $this->assertSame($vatCategoryRate, $vatBreakdown->getVatCategoryRate()?->getValueRounded());
        $this->assertSame($vatExemptionReasonText, $vatBreakdown->getVatExemptionReasonText());
    }

    public static function provideBrCo17_success(): \Generator
    {
        yield 'BR-CO-17 Success #1' => [
            'vatCategoryTaxableAmount' => 1000,
            'vatCategoryTaxAmount' => 250,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 25
        ];
        yield 'BR-CO-17 Success #2' => [
            'vatCategoryTaxableAmount' => -6491.34,
            'vatCategoryTaxAmount' => -1622.84,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 25
        ];
        yield 'BR-CO-17 Success #3' => [
            'vatCategoryTaxableAmount' => 2141.05,
            'vatCategoryTaxAmount' => 299.75,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 14
        ];
        yield 'BR-CO-17 Success #4' => [
            'vatCategoryTaxableAmount' => 2141.05,
            'vatCategoryTaxAmount' => .00,
            'vatCategoryCode' => VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX,
            'vatCategoryRate' => null,
            'vatExemptionReasonText' => 'Hoobastank'
        ];
        yield 'BR-CO-17 Success #5' => [
            'vatCategoryTaxableAmount' => 2141.19,
            'vatCategoryTaxAmount' => 44.96,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 2.1
        ];
        yield 'BR-CO-17 Success #6' => [
            'vatCategoryTaxableAmount' => 2141.19,
            'vatCategoryTaxAmount' => 0.0,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 0
        ];
        yield 'BR-CO-17 Success #7' => [
            'vatCategoryTaxableAmount' => -2141.19,
            'vatCategoryTaxAmount' => -117.77,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 5.5
        ];
        yield 'BR-CO-17 Success #8' => [
            'vatCategoryTaxableAmount' => -25.00,
            'vatCategoryTaxAmount' => 0.00,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 0
        ];
        yield 'BR-CO-17 Success #9' => [
            'vatCategoryTaxableAmount' => -2141.19,
            'vatCategoryTaxAmount' => -117.77,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 5.5
        ];
        yield 'BR-CO-17 Success #10' => [
            'vatCategoryTaxableAmount' => 6491.34,
            'vatCategoryTaxAmount' => 1622.84,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 25
        ];
    }

    /**
     * @test
     * @testdox BR-CO-17 : VAT category tax amount (BT-117) = VAT category taxable amount (BT-116) x (VAT category rate (BT-119) / 100), rounded to two decimals.
     * @dataProvider provideBrCo17_error
     */
    public function brCo17_error(
        float $vatCategoryTaxableAmount,
        float $vatCategoryTaxAmount,
        VatCategory $vatCategoryCode,
        ?float $vatCategoryRate
    ): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(
            $vatCategoryTaxableAmount,
            $vatCategoryTaxAmount,
            $vatCategoryCode,
            $vatCategoryRate
        );
    }

    public static function provideBrCo17_error(): \Generator
    {
        yield 'BR-CO-17 Error #1' => [
            'vatCategoryTaxableAmount' => 1000,
            'vatCategoryTaxAmount' => 251,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 25
        ];
        yield 'BR-CO-17 Error #2' => [
            'vatCategoryTaxableAmount' => 2141.19,
            'vatCategoryTaxAmount' => 43.91,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 2.1
        ];
        yield 'BR-CO-17 Error #3' => [
            'vatCategoryTaxableAmount' => 2141.194,
            'vatCategoryTaxAmount' => 43.91,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 2.1
        ];
        yield 'BR-CO-17 Error #4' => [
            'vatCategoryTaxableAmount' => 2141.19,
            'vatCategoryTaxAmount' => 43.919,
            'vatCategoryCode' => VatCategory::STANDARD_RATE,
            'vatCategoryRate' => 2.1
        ];
    }

    /**
     * @test
     * @testdox : BR-CO-18 : An Invoice shall at least have one VAT breakdown group (BG-23).
     */
    public function brCo18_success(): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20
            ),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                100,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            []
        ));

        $this->assertEquals(1, count($invoice->getVatBreakdowns()));
    }

    /**
     * @test
     * @testdox : BR-CO-18 : An Invoice shall at least have one VAT breakdown group (BG-23).
     */
    public function brCo18_error(): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null,
            [],
            []
        );
    }

    /**
     * @test
     * @testdox BR-CO-19 : If Invoicing period (BG-14) is used, the Invoicing period start date (BT-73) or the Invoicing period end date (BT-74) shall be filled, or both.
     * @dataProvider provideBrCo19_success
     */
    public function brCo19_success(?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): void
    {
        $invoicingPeriod = new InvoicingPeriod($startDate, $endDate);

        $this->assertInstanceOf(InvoicingPeriod::class, $invoicingPeriod);
        $this->assertEquals($startDate, $invoicingPeriod->getStartDate());
        $this->assertEquals($endDate, $invoicingPeriod->getEndDate());
    }

    public static function provideBrCo19_success(): \Generator
    {
        yield 'Invoicing period start date (BT-73) is present' => [
            'startDate' => new \DateTimeImmutable('2021-01-02'),
            'endDate' => null
        ];
        yield 'Invoicing period end date (BT-74) is present' => [
            'startDate' => null,
            'endDate' => new \DateTimeImmutable('2021-01-03')
        ];
        yield 'Invoicing period start date (BT-73) and Invoicing period end date (BT-74) are present' => [
            'startDate' => new \DateTimeImmutable('2021-01-02'),
            'endDate' => new \DateTimeImmutable('2021-01-03')
        ];
    }


    /**
     * @test
     * @testdox BR-CO-19 : If Invoicing period (BG-14) is used, the Invoicing period start date (BT-73) or the Invoicing period end date (BT-74) shall be filled, or both.
     */
    public function brCo19_error(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLinePeriod(null, null);
    }

    /**
     * @test
     * @testdox BR-CO-20 : If Invoice line period (BG-26) is used, the Invoice line period start date (BT-134) or the Invoice line period end date (BT-135) shall be filled, or both.
     * @dataProvider provideBrCo20_success
     */
    public function brCo20_success(?\DateTimeInterface $startDate, ?\DateTimeInterface $endDate): void
    {
        $invoiceLinePeriod = new InvoiceLinePeriod($startDate, $endDate);

        $this->assertInstanceOf(InvoiceLinePeriod::class, $invoiceLinePeriod);
        $this->assertEquals($startDate, $invoiceLinePeriod->getStartDate());
        $this->assertEquals($endDate, $invoiceLinePeriod->getEndDate());
    }

    public static function provideBrCo20_success(): \Generator
    {
        yield 'Invoice line period start date (BT-134) is present' => [
            'startDate' => new \DateTimeImmutable('2021-01-02'),
            'endDate' => null
        ];
        yield 'Invoice line period end date (BT-135) is present' => [
            'startDate' => null,
            'endDate' => new \DateTimeImmutable('2021-01-03')
        ];
        yield 'Invoice line period start date (BT-134) and Invoice line period end date (BT-135) are present' => [
            'startDate' => new \DateTimeImmutable('2021-01-02'),
            'endDate' => new \DateTimeImmutable('2021-01-03')
        ];
    }

    /**
     * @test
     * @testdox BR-CO-20 : If Invoice line period (BG-26) is used, the Invoice line period start date (BT-134) or the Invoice line period end date (BT-135) shall be filled, or both.
     */
    public function brCo20_error(): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLinePeriod(null, null);
    }

    /**
     * @test
     * @testdox BR-CO-21 : Each Document level allowance (BG-20) shall contain a Document level allowance reason (BT-97) or a Document level allowance reason code (BT-98), or both.
     * @dataProvider provideBrCo21_success
     */
    public function brCo21_success(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(14, VatCategory::STANDARD_RATE, $reason, $reasonCode, vatRate: 20);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
        $this->assertEquals($reason, $documentLevelAllowance->getReason());
        $this->assertEquals($reasonCode, $documentLevelAllowance->getReasonCode());
    }

    public static function provideBrCo21_success(): \Generator
    {
        yield 'Document level allowance reason (BT-97) is present' => [
            'reason' => 'Reason',
            'reasonCode' => null
        ];
        yield 'Document level allowance reason code (BT-98) is present' => [
            'reason' => null,
            'reasonCode' => AllowanceReasonCode::STANDARD
        ];
        yield 'Document level allowance reason (BT-97) and Document level allowance reason code (BT-98) are present' => [
            'reason' => 'Reason',
            'reasonCode' => AllowanceReasonCode::STANDARD
        ];
    }

    /**
     * @test
     * @testdox BR-CO-21 : Each Document level allowance (BG-20) shall contain a Document level allowance reason (BT-97) or a Document level allowance reason code (BT-98), or both.
     * @dataProvider provideBrCo21_error
     */
    public function brCo21_error(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(14, VatCategory::STANDARD_RATE, $reason, $reasonCode, vatRate: 20);
    }

    public static function provideBrCo21_error(): \Generator
    {
        yield 'Document level allowance reason (BT-97) as an empty string' => [
            'reason' => '',
            'reasonCode' => null
        ];
        yield 'Document level allowance reason (BT-97) and Document level allowance reason code (BT-98) are null' => [
            'reason' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-22 : Each Document level charge (BG-21) shall contain a Document level charge reason (BT-104) or a Document level charge reason code (BT-105), or both.
     * @dataProvider provideBrCo22_success
     */
    public function brCo22_success(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $documentLevelCharge = new DocumentLevelCharge(14, VatCategory::STANDARD_RATE, $reason, $reasonCode, vatRate: 20);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
        $this->assertEquals($reason, $documentLevelCharge->getReason());
        $this->assertEquals($reasonCode, $documentLevelCharge->getReasonCode());
    }

    public static function provideBrCo22_success(): \Generator
    {
        yield 'Document level charge reason (BT-104) is present' => [
            'reason' => 'Reason',
            'reasonCode' => null
        ];
        yield 'Document level charge reason code (BT-105) is present' => [
            'reason' => null,
            'reasonCode' => ChargeReasonCode::ADVERTISING
        ];
        yield 'Document level charge reason (BT-104) and Document level charge reason code (BT-105) are present' => [
            'reason' => 'Reason',
            'reasonCode' => ChargeReasonCode::ADVERTISING
        ];
    }

    /**
     * @test
     * @testdox BR-CO-22 : Each Document level charge (BG-21) shall contain a Document level charge reason (BT-104) or a Document level charge reason code (BT-105), or both.
     * @dataProvider provideBrCo22_error
     */
    public function brCo22_error(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(14, VatCategory::STANDARD_RATE, $reason, $reasonCode);
    }

    public static function provideBrCo22_error(): \Generator
    {
        yield 'Document level charge reason (BT-104) as an empty string' => [
            'reason' => '',
            'reasonCode' => null
        ];
        yield 'Document level charge reason (BT-104) and Document level charge reason code (BT-105) are null' => [
            'reason' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-23 : Each Invoice line allowance (BG-27) shall contain an Invoice line allowance reason (BT-139) or an Invoice line allowance reason code (BT-140), or both.
     * @dataProvider provideBrCo23_success
     */
    public function brCo23_success(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $invoiceLineAllowance = new InvoiceLineAllowance(14, $reason, $reasonCode);

        $this->assertInstanceOf(InvoiceLineAllowance::class, $invoiceLineAllowance);
        $this->assertEquals($reason, $invoiceLineAllowance->getReason());
        $this->assertEquals($reasonCode, $invoiceLineAllowance->getReasonCode());
    }

    public static function provideBrCo23_success(): \Generator
    {
        yield 'Invoice line allowance reason (BT-139) is present' => [
            'reason' => 'Reason',
            'reasonCode' => null
        ];
        yield 'Invoice line allowance reason code (BT-140) is present' => [
            'reason' => null,
            'reasonCode' => AllowanceReasonCode::STANDARD
        ];
        yield 'Invoice line allowance reason (BT-139) and Invoice line allowance reason code (BT-140) are present' => [
            'reason' => 'Reason',
            'reasonCode' => AllowanceReasonCode::STANDARD
        ];
    }

    /**
     * @test
     * @testdox BR-CO-23 : Each Invoice line allowance (BG-27) shall contain an Invoice line allowance reason (BT-139) or an Invoice line allowance reason code (BT-140), or both.
     * @dataProvider provideBrCo23_error
     */
    public function brCo23_error(?string $reason, ?AllowanceReasonCode $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLineAllowance(14, $reason, $reasonCode);
    }

    public static function provideBrCo23_error(): \Generator
    {
        yield 'Invoice line allowance reason (BT-139) as an empty string' => [
            'reason' => '',
            'reasonCode' => null
        ];
        yield 'Invoice line allowance reason (BT-139) and Invoice line allowance reason code (BT-140) are null' => [
            'reason' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-24 : Each Invoice line charge (BG-28) shall contain an Invoice line charge reason (BT-144) or an Invoice line charge reason code (BT-145), or both.
     * @dataProvider provideBrCo24_success
     */
    public function brCo24_success(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $invoiceLineCharge = new InvoiceLineCharge(10, $reason, $reasonCode);

        $this->assertInstanceOf(InvoiceLineCharge::class, $invoiceLineCharge);
        $this->assertEquals($reason, $invoiceLineCharge->getReason());
        $this->assertEquals($reasonCode, $invoiceLineCharge->getReasonCode());
    }

    public static function provideBrCo24_success(): \Generator
    {
        yield 'Invoice line charge reason (BT-144) is present' => [
            'reason' => 'Reason',
            'reasonCode' => null
        ];
        yield 'Invoice line charge reason code (BT-145) is present' => [
            'reason' => null,
            'reasonCode' => ChargeReasonCode::ADVERTISING
        ];
        yield 'Invoice line charge reason (BT-144) and Invoice line charge reason code (BT-145) are present' => [
            'reason' => 'Reason',
            'reasonCode' => ChargeReasonCode::ADVERTISING
        ];
    }

    /**
     * @test
     * @testdox BR-CO-24 : Each Invoice line charge (BG-28) shall contain an Invoice line charge reason (BT-144) or an Invoice line charge reason code (BT-145), or both.
     * @dataProvider provideBrCo24_error
     */
    public function brCo24_error(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new InvoiceLineCharge(10, $reason, $reasonCode);
    }

    public static function provideBrCo24_error(): \Generator
    {
        yield 'Invoice line charge reason (BT-144) as an empty string' => [
            'reason' => '',
            'reasonCode' => null
        ];
        yield 'Invoice line charge reason (BT-144) and Invoice line charge reason code (BT-145) are null' => [
            'reason' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-25 : In case the Amount due for payment (BT-115) is positive, either the Payment due date (BT-9) or the Payment terms (BT-20) shall be present.
     * @dataProvider provideBrCo25_success
     */
    public function brCo25_success(float $amountDueForPayment, ?\DateTimeInterface $paymentDueDate, ?string $paymentTerms): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                100,
                100,
                120,
                $amountDueForPayment,
                invoiceTotalVatAmount: 20
            ),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                100,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            $paymentDueDate,
            $paymentTerms,
            [],
            []
        ));

        if ($invoice->getDocumentTotals()->getAmountDueForPayment()->getValueRounded() > 0) {
            $this->assertTrue($paymentDueDate || $paymentTerms);
        } else {
            $this->assertTrue(true);
        }
    }

    public static function provideBrCo25_success(): \Generator
    {
        yield 'Amount due for payment (BT-115) is positive and the Payment due date (BT-9) is present' => [
            'amountDueForPayment' => 120,
            'paymentDueDate' => new \DateTimeImmutable('2021-01-02'),
            'paymentTerms' => null
        ];

        yield 'Amount due for payment (BT-115) is positive and the Payment terms (BT-20) is present' => [
            'amountDueForPayment' => 120,
            'paymentDueDate' => null,
            'paymentTerms' => '30 JOURS NETS'
        ];

        yield 'Amount due for payment (BT-115) is positive, the Payment due date (BT-9) and the Payment terms (BT-20) are present' => [
            'amountDueForPayment' => 120,
            'paymentDueDate' => new \DateTimeImmutable('2021-01-02'),
            'paymentTerms' => '30 JOURS NETS'
        ];
    }

    /**
     * @test
     * @testdox BR-CO-25 : In case the Amount due for payment (BT-115) is positive, either the Payment due date (BT-9) or the Payment terms (BT-20) shall be present.
     * @dataProvider provideBrCo25_error
     */
    public function brCo25_error(float $amountDueForPayment, ?\DateTimeInterface $paymentDueDate, ?string $paymentTerms): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, $amountDueForPayment),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            $paymentDueDate,
            $paymentTerms,
            [],
            []
        );
    }

    public static function provideBrCo25_error(): \Generator
    {
        yield 'Amount due for payment (BT-115) is positive, the Payment due date (BT-9) and the Payment terms (BT-20) are not present' => [
            'amountDueForPayment' => 12.2,
            'paymentDueDate' => null,
            'paymentTerms' => null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-26 : In order for the buyer to automatically identify a supplier, the Seller identifier (BT-29), the Seller legal registration identifier (BT-30) and/or the Seller VAT identifier (BT-31) shall be present.
     * @dataProvider provideBrCo26_success
     * @param array<int, SellerIdentifier> $identifiers
     */
    public function brCo26_success(
        array $identifiers,
        ?LegalRegistrationIdentifier $legalRegistrationIdentifier,
        ?VatIdentifier $vatIdentifier
    ): void
    {
        $invoice = (new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                $identifiers,
                $legalRegistrationIdentifier,
                $vatIdentifier
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20
            ),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                100,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
            sellerTaxRepresentativeParty: new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ));

        $seller = $invoice->getSeller();
        $this->assertTrue(!empty($seller->getIdentifiers()) || null !== $seller->getLegalRegistrationIdentifier() || null !== $seller->getVatIdentifier());
    }

    public static function provideBrCo26_success(): \Generator
    {
        yield 'Seller identifier (BT-29)' => [
            'identifiers' => [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            'legalRegistrationIdentifier' => null,
            'vatIdentifier' => null
        ];
        yield 'Seller legal registration identifier (BT-30)' => [
            'identifiers' => [],
            'legalRegistrationIdentifier' => new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            'vatIdentifier' => null
        ];
        yield 'Seller VAT identifier (BT-31)' => [
            'identifiers' => [],
            'legalRegistrationIdentifier' => null,
            'vatIdentifier' => new VatIdentifier('FR88100000009')
        ];
        yield 'Seller identifier (BT-29) and Seller legal registration identifier (BT-30)' => [
            'identifiers' => [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            'legalRegistrationIdentifier' => new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            'vatIdentifier' => null
        ];
        yield 'Seller identifier (BT-29) and Seller VAT identifier (BT-31)' => [
            'identifiers' => [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            'legalRegistrationIdentifier' => null,
            'vatIdentifier' => new VatIdentifier('FR88100000009')
        ];
        yield 'Seller legal registration identifier (BT-30) and Seller VAT identifier (BT-31)' => [
            'identifiers' => [],
            'legalRegistrationIdentifier' => new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            'vatIdentifier' => new VatIdentifier('FR88100000009')
        ];
        yield 'Seller identifier (BT-29) and Seller legal registration identifier (BT-30) and Seller VAT identifier (BT-31)' => [
            'identifiers' => [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            'legalRegistrationIdentifier' => new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            'vatIdentifier' => new VatIdentifier('FR88100000009')
        ];
    }

    /**
     * @test
     * @testdox BR-CO-26 : In order for the buyer to automatically identify a supplier, the Seller identifier (BT-29), the Seller legal registration identifier (BT-30) and/or the Seller VAT identifier (BT-31) shall be present.
     * @dataProvider provideBrCo26_error
     * @param array<int, SellerIdentifier> $identifiers
     */
    public function brCo26_error(
        array $identifiers,
        ?LegalRegistrationIdentifier $legalRegistrationIdentifier,
        ?VatIdentifier $vatIdentifier
    ): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                $identifiers,
                $legalRegistrationIdentifier,
                $vatIdentifier
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD_RATE, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null,
            [],
            []
        );
    }

    public static function provideBrCo26_error(): \Generator
    {
        yield 'No field are filled in' => [
            'identifiers' => [],
            'legalRegistrationIdentifier' => null,
            'vatIdentifier' => null
        ];
    }
}
