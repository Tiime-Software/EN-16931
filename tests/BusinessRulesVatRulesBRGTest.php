<?php

namespace Tiime\EN16931\Tests;

use PHPUnit\Framework\TestCase;
use Tiime\EN16931\BusinessTermsGroup\Buyer;
use Tiime\EN16931\BusinessTermsGroup\BuyerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\DeliverToAddress;
use Tiime\EN16931\BusinessTermsGroup\DeliveryInformation;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelAllowance;
use Tiime\EN16931\BusinessTermsGroup\DocumentLevelCharge;
use Tiime\EN16931\BusinessTermsGroup\DocumentTotals;
use Tiime\EN16931\BusinessTermsGroup\InvoiceLine;
use Tiime\EN16931\BusinessTermsGroup\InvoicingPeriod;
use Tiime\EN16931\BusinessTermsGroup\ItemInformation;
use Tiime\EN16931\BusinessTermsGroup\LineVatInformation;
use Tiime\EN16931\BusinessTermsGroup\PriceDetails;
use Tiime\EN16931\BusinessTermsGroup\ProcessControl;
use Tiime\EN16931\BusinessTermsGroup\Seller;
use Tiime\EN16931\BusinessTermsGroup\SellerPostalAddress;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativePostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\CountryAlpha2Code;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\DataType\VatExoneration;
use Tiime\EN16931\Invoice;

class BusinessRulesVatRulesBRGTest extends TestCase
{
    /**
     * @test
     * @testdox BR-G-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Export outside the EU”
     * shall contain in the VAT breakdown (BG-23) exactly one VAT category code (BT-118) equal with "Export outside the
     * EU".
     * @dataProvider provideBrG1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brG1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
    {
        $invoice = new Invoice(
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
            $documentTotal,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            $documentLevelCharges
        );

        $this->assertInstanceOf(Invoice::class,  $invoice);
    }

    public static function provideBrG1Success(): \Generator
    {
        yield 'BR-G-1 Success #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(1000, 200, VatCategory::STANDARD_RATE, 20),
                new VatBreakdown(1000, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'documentTotals' => new DocumentTotals(
                2000,
                2000,
                2200,
                2200,
                invoiceTotalVatAmount: 200,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-G-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Export outside the EU”
     * shall contain in the VAT breakdown (BG-23) exactly one VAT category code (BT-118) equal with "Export outside the
     * EU".
     * @dataProvider provideBrG1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brG1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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
            $documentTotal,
            $vatBreakdowns,
            $invoiceLines,
            null,
            null,
            new \DateTimeImmutable(),
            null,
            $documentLevelAllowances,
            $documentLevelCharges
        );
    }

    public static function provideBrG1Error(): \Generator
    {
        yield 'BR-G-1 Error #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD_RATE, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(2000, 400, VatCategory::STANDARD_RATE, 20)
            ],
            'documentTotals' => new DocumentTotals(
                2000,
                2000,
                2400,
                2400,
                invoiceTotalVatAmount: 400,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-G-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Export outside the EU” shall contain the Seller VAT Identifier (BT-31) or the Seller tax
     * representative VAT identifier (BT-63).
     * @dataProvider provideBrG2Success
     */
    public function brG2_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR956454'),
            ),
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrG2Success(): \Generator
    {
        yield 'BR-G-2 - Only (Seller) VatIdentifier' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-G-2 - Only (TaxRepresentativeParty) VatIdentifier' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];
    }

    /**
     * @test
     * @testdox BR-G-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Export outside the EU” shall contain the Seller VAT Identifier (BT-31) or the Seller tax
     * representative VAT identifier (BT-63).
     * @dataProvider provideBrG2Error
     */
    public function brG2_error(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $this->expectException(\Exception::class);

        new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR956454'),
            ),
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );
    }

    public static function provideBrG2Error(): \Generator
    {
        yield 'BR-G-2 - (Seller) VatIdentifier and (TaxRepresentativeParty) VatIdentifier' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-G-2 - No field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => null
        ];
    }

    /**
     * @test
     * @testdox BR-G-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Export outside the EU” shall contain the Seller VAT Identifier (BT-31) or the
     * Seller tax representative VAT identifier (BT-63).
     * @dataProvider provideBrG3Success
     */
    public function brG3_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR956454'),
            ),
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [
                new DocumentLevelAllowance(0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
            ],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrG3Success(): \Generator
    {
        yield 'BR-G-3 - Only (Seller) VatIdentifier' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-G-3 - Only (TaxRepresentativeParty) VatIdentifier' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];
    }

    /**
     * @test
     * @testdox BR-G-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Export outside the EU” shall contain the Seller VAT Identifier (BT-31) or the
     * Seller tax representative VAT identifier (BT-63).
     */
    public function brG3_error(): void
    {
        $this->markTestSkipped('Same error case as BR-G-2 that\'s why BR-G-2 exception is thrown before BR-G-3 exception');
    }

    /**
     * @test
     * @testdox BR-G-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Export outside the EU" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrG5Success
     */
    public function brG5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrG5Success(): \Generator
    {
        yield 'BR-G-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Export outside the EU" the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrG5Error
     */
    public function brG5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, $invoicedItemVatRate);
    }

    public static function provideBrG5Error(): \Generator
    {
        yield 'BR-G-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-G-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-G-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-G-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Export outside the EU" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrG6Success
     */
    public function brG6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrG6Success(): \Generator
    {
        yield 'BR-G-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Export outside the EU" the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrG6Error
     */
    public function brG6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrG6Error(): \Generator
    {
        yield 'BR-G-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-G-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-G-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-G-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Export outside the EU" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrG7Success
     */
    public function brG7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrG7Success(): \Generator
    {
        yield 'BR-G-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-G-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Export outside the EU" the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrG7Error
     */
    public function brG7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrG7Error(): \Generator
    {
        yield 'BR-G-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-G-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-G-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-G-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is "Export outside the EU" the
     * VAT category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT-151, BT-95, BT-102) are “Export outside the EU".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrG8Success
     */
    public function brG8_success(
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
                new VatIdentifier('FR978515485'),
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

    public static function provideBrG8Success(): \Generator
    {
        yield "single invoice line" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
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
                new VatBreakdown(100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
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
                new VatBreakdown(-100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0)
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
                new VatBreakdown(-100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0)
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
                new VatBreakdown(100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
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
                new VatBreakdown(100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
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
                new VatBreakdown(100, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0)
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-G-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is "Export outside the EU" the
     * VAT category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT-151, BT-95, BT-102) are “Export outside the EU".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrG8Error
     */
    public function brG8_error(
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

    public static function provideBrG8Error(): \Generator
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
                new VatBreakdown(50, 10, VatCategory::STANDARD_RATE, 20),
                new VatBreakdown(50, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-G-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Export outside the EU” shall be 0 (zero).
     */
    public function brG9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank');

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-G-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Export outside the EU” shall be 0 (zero).
     * @dataProvider provideBrG9Error
     */
    public function brG9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, vatExemptionReasonText: 'Hoobastank');
    }

    public static function provideBrG9Error(): \Generator
    {
        yield 'BR-G-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-G-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }

    /**
     * @test
     * @testdox BR-G-10 : A VAT Breakdown (BG-23) with the VAT Category code (BT-118) "Export outside the EU" shall have
     * a VAT exemption reason code (BT-121), meaning "Export outside the EU" or the VAT exemption reason text (BT-120)
     * "Export outside the EU" (or the equivalent standard text in another language).
     * @dataProvider provideBrG10Success
     */
    public function brG10_success(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $vatBreakdown = new VatBreakdown(0, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, $reasonText, $reasonCode);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    public static function provideBrG10Success(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => VatExoneration::EXPORT_OUTSIDE_THE_EU,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => null,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => VatExoneration::EXPORT_OUTSIDE_THE_EU,
        ];
    }

    /**
     * @test
     * @testdox BR-G-10 : A VAT Breakdown (BG-23) with the VAT Category code (BT-118) "Export outside the EU" shall have
     * a VAT exemption reason code (BT-121), meaning "Export outside the EU" or the VAT exemption reason text (BT-120)
     * "Export outside the EU" (or the equivalent standard text in another language).
     * @dataProvider provideBrG10Error
     */
    public function brG10_error(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(0, 0, VatCategory::FREE_EXPORT_ITEM_TAX_NOT_CHARGED, 0, $reasonText, $reasonCode);
    }

    public static function provideBrG10Error(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => null
        ];
    }
}
