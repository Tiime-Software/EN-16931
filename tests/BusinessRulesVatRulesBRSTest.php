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
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativeParty;
use Tiime\EN16931\BusinessTermsGroup\SellerTaxRepresentativePostalAddress;
use Tiime\EN16931\BusinessTermsGroup\VatBreakdown;
use Tiime\EN16931\DataType\AllowanceReasonCode;
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

class BusinessRulesVatRulesBRSTest extends TestCase
{
    /**
     * @test
     * @testdox BR-S-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Standard rated” shall
     * contain in the VAT breakdown (BG-23) at least one VAT category code (BT-118) equal with "Standard rated".
     * @dataProvider provideBrS1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brS1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrS1Success(): \Generator
    {
        yield 'BR-S-1 Success #1' => [
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
                    new InvoiceLineIdentifier("A2"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    2000,
                    new PriceDetails(2000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(3000, 600, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                3000,
                3000,
                3600,
                3600,
                invoiceTotalVatAmount: 600,
            )
        ];
        yield 'BR-S-1 Success #2' => [
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
            ],
            'documentLevelAllowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD, reasonCode: AllowanceReasonCode::STANDARD, vatRate: 20)
            ],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(900, 180, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                1000,
                900,
                1080,
                1080,
                invoiceTotalVatAmount: 180,
                sumOfAllowancesOnDocumentLevel: 100,
            )
        ];
        yield 'BR-S-1 Success #3' => [
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
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20)
            ],
            'vatBreakdowns' => [
                new VatBreakdown(1100, 220, VatCategory::STANDARD, 20)
            ],
            'documentTotals' => new DocumentTotals(
                1000,
                1100,
                1320,
                1320,
                invoiceTotalVatAmount: 220,
                sumOfChargesOnDocumentLevel: 100,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-S-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT102) is “Standard rated” shall
     * contain in the VAT breakdown (BG-23) at least one VAT category code (BT-118) equal with "Standard rated".
     * @dataProvider provideBrS1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brS1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

    public static function provideBrS1Error(): \Generator
    {
        yield 'BR-S-1 Error #1' => [
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
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [],
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
     * @testdox BR-S-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     * @dataProvider provideBrS2Success
     */
    public function brS2_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
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
                3600,
                3600,
                invoiceTotalVatAmount: 600,
            ),
            [
                new VatBreakdown(3000, 600, VatCategory::STANDARD, 20)
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
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

    public static function provideBrS2Success(): \Generator
    {
        yield 'BR-S-2 - Only Seller VatIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-S-2 - Only Seller TaxRegistrationIdentifier field' => [
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

        yield 'BR-S-2 - Only SellerTaxRepresentativeParty field' => [
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

        yield 'BR-S-2 - Seller VatIdentifier and Seller TaxRegistrationIdentifier fields' => [
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

        yield 'BR-S-2 - Seller VatIdentifier and SellerTaxRepresentativeParty fields' => [
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

        yield 'BR-S-2 - Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
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

        yield 'BR-S-2 - Seller VatIdentifier and Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
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
     * @testdox BR-S-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax registration
     * identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS2_error(): void
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
                3600,
                3600,
                invoiceTotalVatAmount: 600,
            ),
            [
                new VatBreakdown(3000, 600, VatCategory::STANDARD, 20)
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
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
     * @testdox BR-S-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     * @dataProvider provideBrS3Success
     */
    public function brS3_success(Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
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
                2900,
                3480,
                3480,
                invoiceTotalVatAmount: 580,
                sumOfAllowancesOnDocumentLevel: 100,
            ),
            [
                new VatBreakdown(2900, 580, VatCategory::STANDARD, 20)
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [
                new DocumentLevelAllowance(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
            ],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrS3Success(): \Generator
    {
        yield 'BR-S-3 - Only Seller VatIdentifier field' => [
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR978515485'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-S-3 - Only Seller TaxRegistrationIdentifier field' => [
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

        yield 'BR-S-3 - Only SellerTaxRepresentativeParty field' => [
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

        yield 'BR-S-3 - Seller VatIdentifier and Seller TaxRegistrationIdentifier fields' => [
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

        yield 'BR-S-3 - Seller VatIdentifier and SellerTaxRepresentativeParty fields' => [
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

        yield 'BR-S-3 - Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
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

        yield 'BR-S-3 - Seller VatIdentifier and Seller TaxRegistrationIdentifier and SellerTaxRepresentativeParty fields' => [
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
     * @testdox BR-S-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS3_error(): void
    {
        $this->markTestSkipped('Same error case as BR-S-2 so BR-S-2 exception is thrown before BR-S-3 exception');
    }

    /**
     * @test
     * @testdox BR-S-4 : An Invoice that contains a Document level charge (BG-21) where the Document level charge VAT
     * category code (BT-102) is “Standard rated” shall contain the Seller VAT Identifier (BT-31), the Seller tax
     * registration identifier (BT-32) and/or the Seller tax representative VAT identifier (BT-63).
     */
    public function brS4(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-S-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Standard rated" the Invoiced item VAT rate (BT-152) shall be greater than zero.
     * @dataProvider provideBrS5Success
     */
    public function brS5_success(?float $invoicedItemVatRate): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::STANDARD, $invoicedItemVatRate);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    public static function provideBrS5Success(): \Generator
    {
        yield 'BR-S-5 Success #1' => [
            'invoicedItemVatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-5 : In an Invoice line (BG-25) where the Invoiced item VAT category code (BT-151) is
     * "Standard rated" the Invoiced item VAT rate (BT-152) shall be greater than zero.
     * @dataProvider provideBrS5Error
     */
    public function brS5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::STANDARD, $invoicedItemVatRate);
    }

    public static function provideBrS5Error(): \Generator
    {
        yield 'BR-S-5 Error #1' => [
            'invoicedItemVatRate' => 0,
        ];
        yield 'BR-S-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-S-5 Error #3' => [
            'invoicedItemVatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-S-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Standard rated" the Document level allowance VAT rate (BT96) shall be greater than zero.
     * @dataProvider provideBrS6Success
     */
    public function brS6_success(?float $vatRate): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    public static function provideBrS6Success(): \Generator
    {
        yield 'BR-S-6 Success #1' => [
            'vatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-6 : In a Document level allowance (BG-20) where the Document level allowance VAT category code
     * (BT-95) is "Standard rated" the Document level allowance VAT rate (BT96) shall be greater than zero.
     * @dataProvider provideBrS6Error
     */
    public function brS6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrS6Error(): \Generator
    {
        yield 'BR-S-6 Error #1' => [
            'vatRate' => 0,
        ];
        yield 'BR-S-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-S-6 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-S-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Standard rated" the Document level charge VAT rate (BT-103) shall be greater than zero.
     * @dataProvider provideBrS7Success
     */
    public function brS7_success(?float $vatRate): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    public static function provideBrS7Success(): \Generator
    {
        yield 'BR-S-7 Success #1' => [
            'vatRate' => 10,
        ];
    }

    /**
     * @test
     * @testdox BR-S-7 : In a Document level charge (BG-21) where the Document level charge VAT category code (BT-102)
     * is "Standard rated" the Document level charge VAT rate (BT-103) shall be greater than zero.
     * @dataProvider provideBrS7Error
     */
    public function brS7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::STANDARD, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrS7Error(): \Generator
    {
        yield 'BR-S-7 Error #1' => [
            'vatRate' => 0,
        ];
        yield 'BR-S-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-S-7 Error #3' => [
            'vatRate' => null,
        ];
    }

    /**
     * @test
     * @testdox BR-S-8 : For each different value of VAT category rate (BT-119) where the VAT category code (BT-118) is
     * "Standard rated", the VAT category taxable amount (BT-116) in a VAT breakdown (BG-23) shall equal the sum of
     * Invoice line net amounts (BT-131) plus the sum of document level charge amounts (BT-99) minus the sum of
     * document level allowance amounts (BT-92) where the VAT category code (BT-151, BT-102, BT-95) is "Standard rated"
     * and the VAT rate (BT-152, BT-103, BT-96) equals the VAT category rate (BT-119).
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrS8Success
     */
    public function brS8_success(
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

    public static function provideBrS8Success(): \Generator
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::STANDARD, 20),
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::STANDARD, 20),
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20),
                new VatBreakdown(100, 10, VatCategory::STANDARD, 10)
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::STANDARD, 10),
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
                new VatBreakdown(-100, -20, VatCategory::STANDARD, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20)
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
                new VatBreakdown(-100, -20, VatCategory::STANDARD, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(60, VatCategory::STANDARD, 'Hoobastank', vatRate: 20)
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(60, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
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
                new VatBreakdown(100, 20, VatCategory::STANDARD, 20),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(60, VatCategory::STANDARD, 'Hoobastank', vatRate: 20)
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(60, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
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
                new VatBreakdown(50, 10, VatCategory::STANDARD, 20),
                new VatBreakdown(50, 5, VatCategory::STANDARD, 10),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 10),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(50, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelAllowance(50, VatCategory::STANDARD, 'Hoobastank', vatRate: 10)
            ],
            'charges' => [
                new DocumentLevelCharge(50, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
                new DocumentLevelCharge(50, VatCategory::STANDARD, 'Hoobastank', vatRate: 10),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-S-8 : For each different value of VAT category rate (BT-119) where the VAT category code (BT-118) is
     * "Standard rated", the VAT category taxable amount (BT-116) in a VAT breakdown (BG-23) shall equal the sum of
     * Invoice line net amounts (BT-131) plus the sum of document level charge amounts (BT-99) minus the sum of
     * document level allowance amounts (BT-92) where the VAT category code (BT-151, BT-102, BT-95) is "Standard rated"
     * and the VAT rate (BT-152, BT-103, BT-96) equals the VAT category rate (BT-119).
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrS8Error
     */
    public function brS8_error(
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

    public static function provideBrS8Error(): \Generator
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
                new VatBreakdown(50, 10, VatCategory::STANDARD, 20),
                new VatBreakdown(100, 10, VatCategory::STANDARD, 10),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::STANDARD, 20),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::STANDARD, 'Hoobastank', vatRate: 20),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-S-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where VAT category code
     * (BT-118) is "Standard rated" shall equal the VAT category taxable amount (BT-116) multiplied by the VAT
     * category rate (BT-119).
     */
    public function brS9(): void
    {
        $this->assertTrue(true, 'Same as BR-CO-17');
    }


    /**
     * @test
     * @testdox BR-S-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Standard rate" shall not have a VAT
     * exemption reason code (BT-121) or VAT exemption reason text (BT-120).
     * @dataProvider provideBrS10Success
     */
    public function brS10_success(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $vatBreakdown = new VatBreakdown(0, 0, VatCategory::STANDARD, 0, $reasonText, $reasonCode);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    public static function provideBrS10Success(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-S-10 : A VAT Breakdown (BG-23) with VAT Category code (BT-118) "Standard rate" shall not have a VAT
     * exemption reason code (BT-121) or VAT exemption reason text (BT-120).
     * @dataProvider provideBrS10Error
     */
    public function brS10_error(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(0, 0, VatCategory::STANDARD, 0, $reasonText, $reasonCode);
    }

    public static function provideBrS10Error(): \Generator
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


}
