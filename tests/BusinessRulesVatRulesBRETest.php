<?php

namespace Tiime\EN16931\Tests;

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
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativePostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\CountryAlpha2Code;
use Tiime\EN16931\DataType\CurrencyCode;
use Tiime\EN16931\DataType\Identifier\InvoiceIdentifier;
use Tiime\EN16931\DataType\Identifier\InvoiceLineIdentifier;
use Tiime\EN16931\DataType\Identifier\SellerIdentifier;
use Tiime\EN16931\DataType\Identifier\SpecificationIdentifier;
use Tiime\EN16931\DataType\Identifier\TaxRegistrationIdentifier;
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\DataType\VatExoneration;
use Tiime\EN16931\Invoice;

class BusinessRulesVatRulesBRETest extends TestCase
{
    /**
     * @test
     * @testdox BR-E-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Exempt from VAT” shall
     * contain exactly one VAT breakdown (BG-23) with the VAT category code (BT-118) equal to "Exempt from VAT".
     * @dataProvider provideBrE1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brE1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

    public static function provideBrE1Success(): \Generator
    {
        yield 'BR-E-1 Success #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(1000, 200, VatCategory::STANDARD, 20),
                new VatBreakdown(1000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
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
     * @testdox BR-E-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Exempt from VAT” shall
     * contain exactly one VAT breakdown (BG-23) with the VAT category code (BT-118) equal to "Exempt from VAT".
     * @dataProvider provideBrE1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brE1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

    public static function provideBrE1Error(): \Generator
    {
        yield 'BR-E-1 Error #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    1000,
                    new PriceDetails(1000),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(2000, 400, VatCategory::STANDARD, 20)
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
     * @testdox BR-E-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Exempt from VAT” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     * @dataProvider provideBrE2Success
     */
    public function brE2_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
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

    public static function provideBrE2Success(): \Generator
    {
        yield 'BR-E-2 - Only Seller VatIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-2 - Only Seller TaxRegistrationIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-2 - Only SellerTaxRepresentativeParty field' => [
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

        yield 'BR-E-2 - Seller VatIdentifier and Seller TaxRegistrationIdentifier fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-2 - Seller VatIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485')
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-E-2 - Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-E-2 - Seller VatIdentifier and Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR986416485'),
                new TaxRegistrationIdentifier('FR995464564')
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
     * @testdox BR-E-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Exempt from VAT” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brE2_error(): void
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
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
            sellerTaxRepresentativeParty: null
        );
    }

    /**
     * @test
     * @testdox BR-E-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Exempt from VAT” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     * @dataProvider provideBrE3Success
     */
    public function brE3_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [
                new DocumentLevelAllowance(0, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
            ],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrE3Success(): \Generator
    {
        yield 'BR-E-3 - Only Seller VatIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-3 - Only Seller TaxRegistrationIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-3 - Only SellerTaxRepresentativeParty field' => [
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

        yield 'BR-E-3 - Seller VatIdentifier and Seller TaxRegistrationIdentifier fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-E-3 - Seller VatIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485')
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-E-3 - Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
                new TaxRegistrationIdentifier('FR995464564')
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-E-3 - Seller VatIdentifier and Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR986416485'),
                new TaxRegistrationIdentifier('FR995464564')
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
     * @testdox BR-E-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Exempt from VAT” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brE3_error(): void
    {
        $this->markTestSkipped('Same error case as BR-E-2 that\'s why BR-E-2 exception is thrown before BR-E-3 exception');
    }

    /**
     * @test
     * @testdox BR-E-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Exempt from VAT", the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrE5Success
     */
    public function brE5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrE5Success(): \Generator
    {
        yield 'BR-E-5 Success #1' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Exempt from VAT", the Invoiced item VAT rate (BT-152) shall be 0 (zero).
     * @dataProvider provideBrE5Error
     */
    public function brE5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, $invoicedItemVatRate);
    }

    public static function provideBrE5Error(): \Generator
    {
        yield 'BR-E-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-E-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-E-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Exempt from VAT", the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrE6Success
     */
    public function brE6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrE6Success(): \Generator
    {
        yield 'BR-E-6 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Exempt from VAT", the Document level allowance VAT rate (BT-96) shall be 0 (zero).
     * @dataProvider provideBrE6Error
     */
    public function brE6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrE6Error(): \Generator
    {
        yield 'BR-E-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-E-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-E-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Exempt from VAT", the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrE7Success
     */
    public function brE7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrE7Success(): \Generator
    {
        yield 'BR-E-7 Success #1' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-E-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Exempt from VAT", the Document level charge VAT rate (BT-103) shall be 0 (zero).
     * @dataProvider provideBrE7Error
     */
    public function brE7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrE7Error(): \Generator
    {
        yield 'BR-E-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-E-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-E-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-E-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is "Exempt from VAT" the VAT
     * category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT151, BT-95, BT-102) are “Exempt from VAT".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrE8Success
     */
    public function brE8_success(
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

    public static function provideBrE8Success(): \Generator
    {
        yield "single invoice line" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
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
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
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
                new VatBreakdown(-100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0)
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
                new VatBreakdown(-100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0)
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
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
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
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
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
                new VatBreakdown(100, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
                new DocumentLevelAllowance(60, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0)
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
                new DocumentLevelCharge(60, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-E-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is "Exempt from VAT" the VAT
     * category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT151, BT-95, BT-102) are “Exempt from VAT".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrE8Error
     */
    public function brE8_error(
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

    public static function provideBrE8Error(): \Generator
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
                new VatBreakdown(50, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::EXEMPT_FROM_TAX, 0),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::EXEMPT_FROM_TAX, 'Hoobastank', vatRate: 0),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-E-9 : The VAT category tax amount (BT-117) In a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) equals "Exempt from VAT" shall equal 0 (zero).
     */
    public function brE9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank');

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-E-9 : The VAT category tax amount (BT-117) In a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) equals "Exempt from VAT" shall equal 0 (zero).
     * @dataProvider provideBrE9Error
     */
    public function brE9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::EXEMPT_FROM_TAX, 0);
    }

    public static function provideBrE9Error(): \Generator
    {
        yield 'BR-E-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-E-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }

    /**
     * @test
     * @testdox BR-E-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Exempt from VAT" shall have a VAT
     * exemption reason code (BT-121) or a VAT exemption reason text (BT-120).
     * @dataProvider provideBrE10Success
     * @throws \Exception
     */
    public function brE10_success(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $vatBreakdown = new VatBreakdown(0, 0, VatCategory::EXEMPT_FROM_TAX, 0, $reasonText, $reasonCode);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    public static function provideBrE10Success(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => VatExoneration::TRAVEL_AGENTS_VAT_SCHEME,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => null,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => VatExoneration::TRAVEL_AGENTS_VAT_SCHEME,
        ];
    }

    /**
     * @test
     * @testdox BR-E-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Exempt from VAT" shall have a VAT
     * exemption reason code (BT-121) or a VAT exemption reason text (BT-120).
     * @dataProvider provideBrE10Error
     */
    public function brE10_error(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(0, 0, VatCategory::EXEMPT_FROM_TAX, 0, $reasonText, $reasonCode);
    }

    public static function provideBrE10Error(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => null
        ];
    }


}
