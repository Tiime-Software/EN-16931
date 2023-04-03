<?php

namespace Tests\Tiime\EN16931;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
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
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;

class BusinessRulesVatRulesBRZTest extends TestCase
{
    /**
     * @test
     * @testdox BR-Z-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "Zero rated"
     * the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrZ5Success
     */
    public function brZ5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::ZERO_RATED_GOODS, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrZ5Success(): \Generator
    {
        yield 'BR-Z-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "Zero rated"
     * the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrZ5Error
     */
    public function brZ5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::ZERO_RATED_GOODS, $invoicedItemVatRate);
    }

    public static function provideBrZ5Error(): \Generator
    {
        yield 'BR-Z-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-Z-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-Z-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Zero rated" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrZ6Success
     */
    public function brZ6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrZ6Success(): \Generator
    {
        yield 'BR-Z-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Zero rated" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrZ6Error
     */
    public function brZ6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrZ6Error(): \Generator
    {
        yield 'BR-Z-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-Z-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-Z-6 Error #3' => [
            'vatRate' => null,
        ];
    }


    /**
     * @test
     * @testdox BR-Z-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Zero rated" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrZ7Success
     */
    public function brZ7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrZ7Success(): \Generator
    {
        yield 'BR-Z-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Zero rated" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrZ7Error
     */
    public function brZ7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrZ7Error(): \Generator
    {
        yield 'BR-Z-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-Z-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-Z-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-Z-8 : In a VAT breakdown (BG-23) where VAT category code (BT-118) is "Zero rated" the VAT
     * category taxable amount (BT-116) shall equal the sum of Invoice line net amount (BT131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99)
     * where the VAT category codes (BT-151, BT-95, BT-102) are “Zero rated".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrZ8Success
     */
    public function brZ8_success(
        DocumentTotals $totals,
        array $vatBreakdowns,
        array $lines,
        array $allowances,
        array $charges,
    ): void {
        $invoice = new Invoice(
            new InvoiceIdentifier('1'),
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
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $totals,
            $vatBreakdowns,
            $lines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $allowances,
            $charges
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrZ8Success(): \Generator
    {
        yield "single invoice line" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::ZERO_RATED_GOODS, 0)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [],
        ];

        yield "multiple invoice lines" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::ZERO_RATED_GOODS, 0)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [],
        ];

        yield "single allowance" => [
            'totals' => new DocumentTotals(
                0,
                -100,
                -100,
                -100,
                invoiceTotalVatAmount: 0,
                sumOfAllowancesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(-100, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0)
            ],
            'charges' => [],
        ];

        yield "multiple allowances" => [
            'totals' => new DocumentTotals(
                0,
                -100,
                -100,
                -100,
                invoiceTotalVatAmount: 0,
                sumOfAllowancesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(-100, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0)
            ],
            'charges' => [],
        ];

        yield "single charge" => [
            'totals' => new DocumentTotals(
                0,
                100,
                100,
                100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
            ],
        ];

        yield "multiple charges" => [
            'totals' => new DocumentTotals(
                0,
                100,
                100,
                100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
            ],
        ];

        yield "lines, allowances and charges" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
                sumOfAllowancesOnDocumentLevel: 100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0)
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-Z-8 : In a VAT breakdown (BG-23) where VAT category code (BT-118) is "Zero rated" the VAT
     * category taxable amount (BT-116) shall equal the sum of Invoice line net amount (BT131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99)
     * where the VAT category codes (BT-151, BT-95, BT-102) are “Zero rated".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrZ8Error
     */
    public function brZ8_error(
        DocumentTotals $totals,
        array $vatBreakdowns,
        array $lines,
        array $allowances,
        array $charges,
    ): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('1'),
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
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            $totals,
            $vatBreakdowns,
            $lines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $allowances,
            $charges
        );
    }

    public static function provideBrZ8Error(): \Generator
    {
        yield "errored taxable amount" => [
            'totals' => new DocumentTotals(
                100,
                100,
                110,
                110,
                invoiceTotalVatAmount: 10,
                sumOfAllowancesOnDocumentLevel: 100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(50, 10, VatCategory::STANDARD, 20),
                new VatBreakdown(50, 0, VatCategory::ZERO_RATED_GOODS, 0),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::ZERO_RATED_GOODS, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::ZERO_RATED_GOODS, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-Z-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "Zero rated" shall equal 0 (zero).
     */
    public function brZ9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::ZERO_RATED_GOODS, 0);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-Z-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "Zero rated" shall equal 0 (zero).
     * @dataProvider provideBrZ9Error
     */
    public function brZ9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::ZERO_RATED_GOODS, 0);
    }

    public static function provideBrZ9Error(): \Generator
    {
        yield 'BR-Z-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-Z-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }
}
