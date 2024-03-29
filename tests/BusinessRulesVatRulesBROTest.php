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
use Tiime\EN16931\DataType\Identifier\LegalRegistrationIdentifier;
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

class BusinessRulesVatRulesBROTest extends TestCase
{
    /**
     * @test
     * @testdox BR-O-1 : An Invoice that contains an Invoice line (BG-25), a Document level allowance (BG-20) or a
     * Document level charge (BG-21) where the VAT category code (BT-151, BT-95 or BT-102) is “Not subject to VAT”
     * shall contain exactly one VAT breakdown group (BG-23) with the VAT category code (BT-118) equal to "Not subject
     * to VAT".
     * @dataProvider provideBrO1Success
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brO1_success(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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
                null,
                new TaxRegistrationIdentifier('FR958954615')
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

    public static function provideBrO1Success(): \Generator
    {
        yield 'BR-O-1 Success #1' => [
            'invoiceLines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    2000,
                    new PriceDetails(2000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null),
                    new ItemInformation("A thing"),
                ),
            ],
            'documentLevelAllowances' => [],
            'documentLevelCharges' => [],
            'vatBreakdowns' => [
                new VatBreakdown(2000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            'documentTotals' => new DocumentTotals(
                2000,
                2000,
                2000,
                2000,
            )
        ];
    }

    /**
     * @test
     * @testdox BR-O-1 :
     * @dataProvider provideBrO1Error
     * @param array<int, InvoiceLine> $invoiceLines
     * @param array<int, DocumentLevelAllowance> $documentLevelAllowances
     * @param array<int, DocumentLevelCharge> $documentLevelCharges
     * @param array<int, VatBreakdown> $vatBreakdowns
     */
    public function brO1_error(array $invoiceLines, array $documentLevelAllowances, array $documentLevelCharges, array $vatBreakdowns, DocumentTotals $documentTotal): void
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

    public static function provideBrO1Error(): \Generator
    {
        yield 'BR-O-1 Error #1' => [
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
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null),
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
     * @testdox BR-O-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Not subject to VAT” shall not contain the Seller VAT identifier (BT-31), the Seller tax
     * representative VAT identifier (BT-63) or the Buyer VAT identifier (BT48).
     * @dataProvider provideBrO2Success
     */
    public function brO2_success(Buyer $buyer, Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            $buyer,
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
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

    public static function provideBrO2Success(): \Generator
    {
        yield 'BR-O-2 - No field' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-2 - (Seller) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-2 - (TaxRepresentativeParty) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
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

        yield 'BR-O-2 - (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR985465151')
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-2 - (Seller) VatIdentifier and (TaxRepresentativeParty) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-O-2 - (Seller) VatIdentifier and (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR9594515'),
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-2 - (TaxRepresentativeParty) VatIdentifier and (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR9594515'),
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
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
     * @testdox BR-O-2 : An Invoice that contains an Invoice line (BG-25) where the Invoiced item VAT category code
     * (BT-151) is “Not subject to VAT” shall not contain the Seller VAT identifier (BT-31), the Seller tax
     * representative VAT identifier (BT-63) or the Buyer VAT identifier (BT48).
     * @dataProvider provideBrO2Error
     */
    public function brO2_error(Buyer $buyer, Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
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
            $buyer,
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
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

    public static function provideBrO2Error(): \Generator
    {
        yield 'BR-O-2 - (Seller) VatIdentifier and (TaxRepresentativeParty) VatIdentifier and (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR9594515'),
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
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
     * @testdox BR-O-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Not subject to VAT” shall not contain the Seller VAT identifier (BT-31), the Seller
     * tax representative VAT identifier (BT-63) or the Buyer VAT identifier (BT-48).
     * @dataProvider provideBrO3Success
     */
    public function brO3_success(Buyer $buyer, Seller $seller, ?SellerTaxRepresentativeParty $sellerTaxRepresentativeParty): void
    {
        $invoice = new Invoice(
            new InvoiceIdentifier('34'),
            new \DateTimeImmutable(),
            InvoiceTypeCode::COMMERCIAL_INVOICE,
            CurrencyCode::EURO,
            (new ProcessControl(new SpecificationIdentifier(SpecificationIdentifier::BASIC)))
                ->setBusinessProcessType('A1'),
            $seller,
            $buyer,
            null,
            new DocumentTotals(
                3000,
                3000,
                3000,
                3000,
                invoiceTotalVatAmount: 0,
            ),
            [
                new VatBreakdown(3000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [
                new DocumentLevelAllowance(0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
            ],
            [],
            sellerTaxRepresentativeParty: $sellerTaxRepresentativeParty
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    public static function provideBrO3Success(): \Generator
    {
        yield 'BR-O-3 - No field' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-3 - (Seller) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-3 - (TaxRepresentativeParty) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
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

        yield 'BR-O-3 - (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR985465151')
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null,
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-3 - (Seller) VatIdentifier and (TaxRepresentativeParty) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                null
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => new SellerTaxRepresentativeParty(
                'SellerTaxRepresentativeParty',
                new VatIdentifier('FR986416485'),
                new SellerTaxRepresentativePostalAddress(CountryAlpha2Code::FRANCE)
            )
        ];

        yield 'BR-O-3 - (Seller) VatIdentifier and (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR9594515'),
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                new VatIdentifier('FR9594515'),
            ),
            'sellerTaxRepresentativeParty' => null
        ];

        yield 'BR-O-3 - (TaxRepresentativeParty) VatIdentifier and (Buyer) VatIdentifier' => [
            'buyer' => new Buyer(
                'Richard Roe',
                new BuyerPostalAddress(CountryAlpha2Code::FRANCE),
                new VatIdentifier('FR9594515'),
            ),
            'seller' => new Seller(
                'John Doe',
                new SellerPostalAddress(CountryAlpha2Code::FRANCE),
                [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
                null,
                null
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
     * @testdox BR-O-3 : An Invoice that contains a Document level allowance (BG-20) where the Document level allowance
     * VAT category code (BT-95) is “Not subject to VAT” shall not contain the Seller VAT identifier (BT-31), the Seller
     * tax representative VAT identifier (BT-63) or the Buyer VAT identifier (BT-48).
     */
    public function brO3_error(): void
    {
        $this->markTestSkipped('Same error case as BR-O-2 that\'s why BR-O-2 exception is thrown before BR-O-3 exception');
    }

    /**
     * @test
     * @testdox BR-O-5 : An Invoice line (BG-25) where the VAT category code (BT-151) is "Not subject to VAT" shall not
     * contain an Invoiced item VAT rate (BT-152).
     */
    public function brO5_success(): void
    {
        $lineVatInformation = new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX);

        $this->assertInstanceOf(LineVatInformation::class, $lineVatInformation);
    }

    /**
     * @test
     * @testdox BR-O-5 : An Invoice line (BG-25) where the VAT category code (BT-151) is "Not subject to VAT" shall not
     * contain an Invoiced item VAT rate (BT-152).
     * @dataProvider provideBrO5Error
     */
    public function brO5_error(?float $invoicedItemVatRate): void
    {
        $this->expectException(\Exception::class);

        new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, $invoicedItemVatRate);
    }

    public static function provideBrO5Error(): \Generator
    {
        yield 'BR-O-5 Error #1' => [
            'invoicedItemVatRate' => 10,
        ];
        yield 'BR-O-5 Error #2' => [
            'invoicedItemVatRate' => -10,
        ];
        yield 'BR-O-5 Error #3' => [
            'invoicedItemVatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-6 : A Document level allowance (BG-20) where VAT category code (BT-95) is "Not subject to VAT"
     * shall not contain a Document level allowance VAT rate (BT-96).
     */
    public function brO6_success(): void
    {
        $documentLevelAllowance = new DocumentLevelAllowance(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank');

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
    }

    /**
     * @test
     * @testdox BR-O-6 : A Document level allowance (BG-20) where VAT category code (BT-95) is "Not subject to VAT"
     * shall not contain a Document level allowance VAT rate (BT-96).
     * @dataProvider provideBrO6Error
     */
    public function brO6_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelAllowance(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrO6Error(): \Generator
    {
        yield 'BR-O-6 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-O-6 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-O-6 Error #3' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-7 : A Document level charge (BG-21) where the VAT category code (BT-102) is "Not subject to VAT"
     * shall not contain a Document level charge VAT rate (BT-103).
     */
    public function brO7_success(): void
    {
        $documentLevelCharge = new DocumentLevelCharge(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank');

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
    }

    /**
     * @test
     * @testdox BR-O-7 : A Document level charge (BG-21) where the VAT category code (BT-102) is "Not subject to VAT"
     * shall not contain a Document level charge VAT rate (BT-103).
     * @dataProvider provideBrO7Error
     */
    public function brO7_error(?float $vatRate): void
    {
        $this->expectException(\Exception::class);

        new DocumentLevelCharge(1, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank', vatRate: $vatRate);
    }

    public static function provideBrO7Error(): \Generator
    {
        yield 'BR-O-7 Error #1' => [
            'vatRate' => 10,
        ];
        yield 'BR-O-7 Error #2' => [
            'vatRate' => -10,
        ];
        yield 'BR-O-7 Error #3' => [
            'vatRate' => 0,
        ];
    }

    /**
     * @test
     * @testdox BR-O-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is " Not subject to VAT" the
     * VAT category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT-151, BT-95, BT-102) are “Not subject to VAT".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrO8Success
     */
    public function brO8_success(
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

    public static function provideBrO8Success(): \Generator
    {
        yield "single invoice line" => [
            'totals' => new DocumentTotals(
                100,
                100,
                100,
                100,
            ),
            'vatBreakdowns' => [
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    100,
                    new PriceDetails(100),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
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
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank')
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(50),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
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
                new VatBreakdown(-100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank')
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
                new VatBreakdown(-100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
                new DocumentLevelAllowance(60, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank')
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
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
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
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    0,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
                new DocumentLevelCharge(60, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
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
                new VatBreakdown(100, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(40, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
                new DocumentLevelAllowance(60, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank')
            ],
            'charges' => [
                new DocumentLevelCharge(40, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
                new DocumentLevelCharge(60, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-O-8 : In a VAT breakdown (BG-23) where the VAT category code (BT-118) is " Not subject to VAT" the
     * VAT category taxable amount (BT-116) shall equal the sum of Invoice line net amounts (BT-131) minus the sum of
     * Document level allowance amounts (BT-92) plus the sum of Document level charge amounts (BT-99) where the VAT
     * category codes (BT-151, BT-95, BT-102) are “Not subject to VAT".
     *
     * @param array<int, VatBreakdown> $vatBreakdowns
     * @param array<int, InvoiceLine> $lines
     * @param array<int, DocumentLevelAllowance> $allowances
     * @param array<int, DocumentLevelCharge> $charges
     *
     * @dataProvider provideBrO8Error
     */
    public function brO8_error(
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

    public static function provideBrO8Error(): \Generator
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
                new VatBreakdown(50, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank'),
            ],
            'lines' => [
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                ),
                new InvoiceLine(
                    new InvoiceLineIdentifier("1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    50,
                    new PriceDetails(0),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            'allowances' => [
                new DocumentLevelAllowance(100, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
            ],
            'charges' => [
                new DocumentLevelCharge(100, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, 'Hoobastank'),
            ],
        ];
    }

    /**
     * @test
     * @testdox BR-O-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Not subject to VAT” shall be 0 (zero).
     */
    public function brO9_success(): void
    {
        $vatBreakdown = new VatBreakdown(1000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, vatExemptionReasonText: 'Hoobastank');

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    /**
     * @test
     * @testdox BR-O-9 : The VAT category tax amount (BT-117) in a VAT breakdown (BG-23) where the VAT category code
     * (BT-118) is “Not subject to VAT” shall be 0 (zero).
     * @dataProvider provideBrO9Error
     */
    public function brO9_error(float $vatCategoryTaxAmount): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(1000, $vatCategoryTaxAmount, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX);
    }

    public static function provideBrO9Error(): \Generator
    {
        yield 'BR-O-9 Error #1' => [
            'vatCategoryTaxAmount' => 10,
        ];
        yield 'BR-O-9 Error #2' => [
            'vatCategoryTaxAmount' => -10,
        ];
    }

    /**
     * @test
     * @testdox BR-O-10 : An Invoice that contains a VAT breakdown group (BG-23) with a VAT category code (BT118)
     * "Not subject to VAT" shall not contain other VAT breakdown groups (BG-23).
     * @dataProvider provideBrO10Success
     */
    public function brO10_success(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $vatBreakdown = new VatBreakdown(0, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null, $reasonText, $reasonCode);

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
    }

    public static function provideBrO10Success(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => VatExoneration::NOT_SUBJECT_TO_VAT,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => null,
        ];

        yield [
            'reasonText' => 'Hoobastank',
            'reasonCode' => VatExoneration::NOT_SUBJECT_TO_VAT,
        ];
    }

    /**
     * @test
     * @testdox BR-O-10 : An Invoice that contains a VAT breakdown group (BG-23) with a VAT category code (BT118)
     * "Not subject to VAT" shall not contain other VAT breakdown groups (BG-23).
     * @dataProvider provideBrO10Error
     */
    public function brO10_error(?string $reasonText, ?VatExoneration $reasonCode): void
    {
        $this->expectException(\Exception::class);

        new VatBreakdown(0, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null, $reasonText, $reasonCode);
    }

    public static function provideBrO10Error(): \Generator
    {
        yield [
            'reasonText' => null,
            'reasonCode' => null
        ];
    }

    /**
     * @test
     * @testdox BR-O-11 :
     */
    public function brO11_success(): void
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
                null,
            ),
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
                new VatBreakdown(3000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null, vatExemptionReasonText: 'Hoobastank')
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
    }

    /**
     * @test
     * @testdox BR-O-11 :
     */
    public function brO11_error(): void
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
                null,
            ),
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
                new VatBreakdown(3000, 0, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null, vatExemptionReasonText: 'Hoobastank'),
                new VatBreakdown(3000, 0, VatCategory::EXEMPT_FROM_TAX, 0, vatExemptionReasonText: 'Hoobastank'),
            ],
            [
                new InvoiceLine(
                    new InvoiceLineIdentifier("A1"),
                    1,
                    UnitOfMeasurement::BOX_REC21,
                    3000,
                    new PriceDetails(3000),
                    new LineVatInformation(VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX),
                    new ItemInformation("A thing"),
                )
            ],
            null,
            null,
            new \DateTimeImmutable(),
            null,
            [],
            [],
        );
    }
}
