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

class BusinessRulesVatRulesBRIPTest extends TestCase
{
    /**
     * @test
     * @testdox BR-IP-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "IPSI" the
     * Invoiced item VAT rate (BT-152) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP5Success
     */
    public function brIP5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrIP5Success(): \Generator
    {
        yield 'BR-IP-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-5 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is "IPSI" the
     * Invoiced item VAT rate (BT-152) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP5Error
     */
    public function brIP5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, $invoicedItemVatRate);
    }

    public static function provideBrIP5Error(): \Generator
    {
        yield 'BR-IP-5 Error #1' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-IP-5 Error #2' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "IPSI" the Document level allowance VAT rate (BT-96) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP6Success
     */
    public function brIP6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrIP6Success(): \Generator
    {
        yield 'BR-IP-6 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-6 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "IPSI" the Document level allowance VAT rate (BT-96) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP6Error
     */
    public function brIP6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrIP6Error(): \Generator
    {
        yield 'BR-IP-6 Error #1' => [
            'vatRate' => -10,
        ];
        yield 'BR-IP-6 Error #2' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "IPSI" the Document level charge VAT rate (BT-103) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP7Success
     */
    public function brIP7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrIP7Success(): \Generator
    {
        yield 'BR-IP-7 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-IP-7 Success #2' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "IPSI" the Document level charge VAT rate (BT-103) shall be 0 (zero) or greater than zero.
     * @dataProvider provideBrIP7Error
     */
    public function brIP7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrIP7Error(): \Generator
    {
        yield 'BR-IP-7 Error #1' => [
            'vatRate' => -10,
        ];
        yield 'BR-IP-7 Error #2' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-IP-8 : For each different value of VAT category rate (BT-119) where the VAT category code (BT118) is
     * "IPSI", the VAT category taxable amount (BT-116) in a VAT breakdown (BG-23) shall equal the sum of Invoice line
     * net amounts (BT-131) plus the sum of document level charge amounts (BT-99) minus the sum of document level
     * allowance amounts (BT-92) where the VAT category code (BT-151, BT-102, BT-95) is “IPSI” and the VAT rate
     * (BT-152, BT-103, BT-96) equals the VAT category rate (BT-119).
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrIP8Success
     */
    public function brIP8_success(
        DocumentTotals $totals,
        array $vatBreakdowns,
        array $lines,
        array $allowances,
        array $charges,
    ): void
    {
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

    public static function provideBrIP8Success(): \Generator
    {
        yield "single invoice line" => [
            'totals' => new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [],
        ];

        yield "multiple invoice lines w/ same rate" => [
            'totals' => new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [],
        ];

        yield "multiple invoice lines w/ different rates" => [
            'totals' => new DocumentTotals(
                200,
                200,
                230,
                230,
                invoiceTotalVatAmount: 30
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20),
                new VatBreakdown(100, 10, VatCategory::CEUTA_AND_MELILLA, 10)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 10),
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
                -120,
                -120,
                invoiceTotalVatAmount: -20,
                sumOfAllowancesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(-100, -20, VatCategory::CEUTA_AND_MELILLA, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20)
            ],
            'charges' => [],
        ];

        yield "multiple allowances" => [
            'totals' => new DocumentTotals(
                0,
                -100,
                -120,
                -120,
                invoiceTotalVatAmount: -20,
                sumOfAllowancesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(-100, -20, VatCategory::CEUTA_AND_MELILLA, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(60, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20)
            ],
            'charges' => [],
        ];

        yield "single charge" => [
            'totals' => new DocumentTotals(
                0,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
            ],
        ];

        yield "multiple charges" => [
            'totals' => new DocumentTotals(
                0,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(60, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
            ],
        ];

        yield "lines, allowances and charges w/ same rate" => [
            'totals' => new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20,
                sumOfAllowancesOnDocumentLevel: 100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 20, VatCategory::CEUTA_AND_MELILLA, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(60, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20)
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(60, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
            ],
        ];

        yield "lines, allowances and charges w/ different rates" => [
            'totals' => new DocumentTotals(
                100,
                100,
                115,
                115,
                invoiceTotalVatAmount: 15,
                sumOfAllowancesOnDocumentLevel: 100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(50, 10, VatCategory::CEUTA_AND_MELILLA, 20),
                new VatBreakdown(50, 5, VatCategory::CEUTA_AND_MELILLA, 10),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 10),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(50, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(50, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 10)
            ],
            'charges' => [
                new DocumentLevelCharge(50, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(50, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 10),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-IP-8 : For each different value of VAT category rate (BT-119) where the VAT category code (BT118) is
     * "IPSI", the VAT category taxable amount (BT-116) in a VAT breakdown (BG-23) shall equal the sum of Invoice line
     * net amounts (BT-131) plus the sum of document level charge amounts (BT-99) minus the sum of document level
     * allowance amounts (BT-92) where the VAT category code (BT-151, BT-102, BT-95) is “IPSI” and the VAT rate
     * (BT-152, BT-103, BT-96) equals the VAT category rate (BT-119).
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrIP8Error
     */
    public function brIP8_error(
        DocumentTotals $totals,
        array $vatBreakdowns,
        array $lines,
        array $allowances,
        array $charges,
    ): void {
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

    public static function provideBrIP8Error(): \Generator
    {
        yield "errored taxable amount" => [
            'totals' => new DocumentTotals(
                100,
                100,
                120,
                120,
                invoiceTotalVatAmount: 20,
                sumOfAllowancesOnDocumentLevel: 100,
                sumOfChargesOnDocumentLevel: 100
            ),
            'vatBreakdowns' => [
                new VatBreakdown(50, 10, VatCategory::CEUTA_AND_MELILLA, 20),
                new VatBreakdown(100, 10, VatCategory::CEUTA_AND_MELILLA, 10),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::CEUTA_AND_MELILLA, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::CEUTA_AND_MELILLA, 'Hoobastank', vatRate: 20),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-IP-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "IPSI" shall equal the VAT category taxable amount (BT-116) multiplied by the VAT category rate
     * (BT-119).
     */
    public function brIP9(): void
    {
        $this->assertTrue(true, 'Same as BR-CO-17');
    }
}
