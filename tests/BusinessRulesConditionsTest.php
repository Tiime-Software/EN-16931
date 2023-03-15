<?php

namespace Tests\Tiime\EN16931;

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
use Tiime\EN16931\DataType\Identifier\VatIdentifier;
use Tiime\EN16931\DataType\InternationalCodeDesignator;
use Tiime\EN16931\DataType\InvoiceTypeCode;
use Tiime\EN16931\DataType\UnitOfMeasurement;
use Tiime\EN16931\DataType\VatCategory;
use Tiime\EN16931\Invoice;

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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null
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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            new \DateTimeImmutable(),
            null,
            null,
            null
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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            DateCode2005::DELIVERY_DATE_TIME,
            null,
            null
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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            CurrencyCode::CANADIAN_DOLLAR,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            new \DateTimeImmutable(),
            DateCode2005::DELIVERY_DATE_TIME,
            null,
            null
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
            new LineVatInformation(VatCategory::STANDARD),
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
     */
    public function brCo9(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-10 : Sum of Invoice line net amount (BT-106) = ∑ Invoice line net amount (BT-131).
     * @dataProvider provideBrCo10Success
     * @param array<int, int> $linesAmount
     */
    public function brCo10_equals(float $total, array $linesAmount): void
    {
        $invoiceLines = [];
        foreach ($linesAmount as $lineAmount) {
            $invoiceLines[] = new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                $lineAmount,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            );
        }

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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals($total, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            $invoiceLines,
            null,
            null,
            null,
            null
        ));

        $invoiceLinesFromObject = $invoice->getInvoiceLines();
        $invoiceLinesTotal = 0.00;
        foreach ($invoiceLinesFromObject as $invoiceLineFromObject) {
            $invoiceLinesTotal += $invoiceLineFromObject->getNetAmount() * $invoiceLineFromObject->getInvoicedQuantity();
        }

        $this->assertEquals($invoice->getDocumentTotals()->getSumOfInvoiceLineNetAmount(), round($invoiceLinesTotal * 100) / 100);
    }

    public static function provideBrCo10Success(): \Generator
    {
        yield 'One invoice line with positive amount' => ['total' => 100, 'lines' => [100.00]];
        yield 'One invoice line with negative amount' => ['total' => -100, 'lines' => [-100.00]];
        yield 'One invoice line with amount equal to 0 (float)' => ['total' => 0, 'lines' => [0.00]];
        yield 'One invoice line with amount equal to 0 (int)' => ['total' => 0, 'lines' => [0]];
        yield 'Two invoice lines with two positives numbers and positive total' => ['total' => 200, 'lines' => [110.00, 90.00]];
        yield 'Two invoice lines with one positive number / one negative number and positive total' => ['total' => 20, 'lines' => [110.00, -90.00]];
        yield 'Two invoice lines with one positive number / one negative number and negative total' => ['total' => -20, 'lines' => [-110.00, 90.00]];
    }

    /**
     * @test
     * @testdox BR-CO-10 : Sum of Invoice line net amount (BT-106) = ∑ Invoice line net amount (BT-131).
     * @dataProvider provideBrCo10Error
     * @param array<int, int> $linesAmount
     */
    public function brCo10_notEquals(float $total, array $linesAmount): void
    {
        $invoiceLines = [];
        foreach ($linesAmount as $lineAmount) {
            $invoiceLines[] = new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                $lineAmount,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            );
        }

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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals($total, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            $invoiceLines,
            null,
            null,
            null,
            null
        ));

        $invoiceLinesFromObject = $invoice->getInvoiceLines();
        $invoiceLinesTotal = 0.00;
        foreach ($invoiceLinesFromObject as $invoiceLineFromObject) {
            $invoiceLinesTotal += $invoiceLineFromObject->getNetAmount() * $invoiceLineFromObject->getInvoicedQuantity();
        }

        $this->assertNotEquals($invoice->getDocumentTotals()->getSumOfInvoiceLineNetAmount(), round($invoiceLinesTotal * 100) / 100);
    }

    public static function provideBrCo10Error(): \Generator
    {
        yield 'Error with two invoice lines' => [200.01, [110.00, 90.00]];
        yield 'Error with one invoice lines' => [-91, [-90.00]];
    }

    /**
     * @test
     * @testdox BR-CO-11 : Sum of allowances on document level (BT-107) = ∑ Document level allowance amount (BT-92).
     */
    public function brCo11(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-12 : Sum of charges on document level (BT-108) = ∑ Document level charge amount (BT-99).
     */
    public function brCo12(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-13 : Invoice total amount without VAT (BT-109) = ∑ Invoice line net amount (BT-131) - Sum of allowances on document level (BT-107) + Sum of charges on document level (BT-108).
     */
    public function brCo13(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-14 : Invoice total VAT amount (BT-110) = ∑ VAT category tax amount (BT-117).
     */
    public function brCo14(): void
    {
        $this->markTestSkipped('@todo');
    }

    /**
     * @test
     * @testdox BR-CO-15 : Invoice total amount with VAT (BT-112) = Invoice total amount without VAT (BT-109) + Invoice total VAT amount (BT-110).
     * @dataProvider provideBrCo15_success
     */
    public function brCo15_success(float $invoiceTotalAmountWithoutVat, ?float $invoiceTotalVatAmount, float $invoiceTotalAmountWithVat, float $amountDueForPayment): void
    {
        $documentTotals = new DocumentTotals(
            0,
            $invoiceTotalAmountWithoutVat,
            $invoiceTotalAmountWithVat,
            $amountDueForPayment,
            invoiceTotalVatAmount: $invoiceTotalVatAmount
        );

        $this->assertInstanceOf(DocumentTotals::class, $documentTotals);
        $this->assertEquals($documentTotals->getInvoiceTotalAmountWithVat(), $documentTotals->getInvoiceTotalAmountWithoutVat() + ($documentTotals->getInvoiceTotalVatAmount() ?? 0));
    }

    public static function provideBrCo15_success(): \Generator
    {
        // BT-109, BT-110, BT-112, BT-115
        yield 'Standard calculation' => [
            1000, 300, 1300, 1300
        ];
        yield 'Standard calculation with VAT to null' => [
            1300, null, 1300, 1300
        ];
        yield 'Standard calculation with VAT to 0' => [
            1300, 0, 1300, 1300
        ];
        yield 'Calculation with Invoice Total Amount Without VAT < 0' => [
            -100, 300, 200, 200
        ];
        yield 'Calculation with Invoice Total Amount Without VAT < 0 and Invoice Total Amount With VAT = 0' => [
            -100, 100, 0.0, 0.0
        ];
        yield 'Calculation with all data = 0' => [
            0.00, 0, 0.0, 0.0
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

        // TODO : more tests like docs + error cases
//        $this->assertEquals($amountDueForPayment, $documentTotals->getAmountDueForPayment());
//        $this->assertEquals($amountDueForPayment, $documentTotals->getInvoiceTotalAmountWithVat() - ($documentTotals->getPaidAmount() ?? 0) + ($documentTotals->getRoundingAmount() ?? 0));
    }

    public static function provideBrCo16_success(): \Generator
    {
        // BT-109, BT-110, BT-112, BT-113, BT-114, BT-115
        yield 'BR-CO-16 Success #1' => [
            1200, 0, 1200, 1000, null, 200.00
        ];
        yield 'BR-CO-16 Success #2' => [
            8250.00, 0, 8250.00, null, null, 8250.0
        ];
        yield 'BR-CO-16 Success #3' => [
            1200.00, 0, 1200.00, 0, null, 1200.00
        ];
        yield 'BR-CO-16 Success #4' => [
            1200.00, 0, 1200.00, 1200, null, 0.0
        ];
        yield 'BR-CO-16 Success #5' => [
            1200.78, 0, 1200.78, 1000.0, 0.22, 201
        ];
        yield 'BR-CO-16 Success #6' => [
            1200.78, 0, 1200.78, null, 0.22, 1201
        ];
        yield 'BR-CO-16 Success #7' => [
            1200.22, 0, 1200.22, null, -0.22, 1200.0
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
        ?float $vatCategoryRate
    ): void
    {
        $vatBreakdown = new VatBreakdown(
            $vatCategoryTaxableAmount,
            $vatCategoryTaxAmount,
            $vatCategoryCode,
            $vatCategoryRate
        );

        $this->assertInstanceOf(VatBreakdown::class, $vatBreakdown);
        $this->assertSame($vatCategoryTaxableAmount, $vatBreakdown->getVatCategoryTaxableAmount());
        $this->assertSame($vatCategoryTaxAmount, $vatBreakdown->getVatCategoryTaxAmount());
        $this->assertSame($vatCategoryCode, $vatBreakdown->getVatCategoryCode());
        $this->assertSame($vatCategoryRate, $vatBreakdown->getVatCategoryRate());
    }

    public static function provideBrCo17_success(): \Generator
    {
        // BT-116, BT-117, BT-118, BT-119
        yield 'BR-CO-17 Success #1' => [
            1000, 250, VatCategory::STANDARD, 25
        ];
        yield 'BR-CO-17 Success #2' => [
            -6491.34, -1622.84, VatCategory::STANDARD, 25
        ];
        yield 'BR-CO-17 Success #3' => [
            2141.05, 299.75, VatCategory::STANDARD, 14
        ];
        yield 'BR-CO-17 Success #4' => [
            2141.05, .00, VatCategory::SERVICE_OUTSIDE_SCOPE_OF_TAX, null
        ];
        yield 'BR-CO-17 Success #5' => [
            2141.19, 44.96, VatCategory::STANDARD, 2.1
        ];
        yield 'BR-CO-17 Success #6' => [
            2141.19, 0.0, VatCategory::STANDARD, 0
        ];
        yield 'BR-CO-17 Success #7' => [
            -2141.19, -117.77, VatCategory::STANDARD, 5.5
        ];
        yield 'BR-CO-17 Success #8' => [
            -25.00, 0.00, VatCategory::STANDARD, 0
        ];
        yield 'BR-CO-17 Success #9' => [
            -2141.19, -117.77, VatCategory::STANDARD, 5.5
        ];
        yield 'BR-CO-17 Success #10' => [
            6491.34, 1622.84, VatCategory::STANDARD, 25
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

        $vatBreakdown = new VatBreakdown(
            $vatCategoryTaxableAmount,
            $vatCategoryTaxAmount,
            $vatCategoryCode,
            $vatCategoryRate
        );
    }

    public static function provideBrCo17_error(): \Generator
    {
        // BT-116, BT-117, BT-118, BT-119
        yield 'BR-CO-17 Error #1' => [
            1000, 251, VatCategory::STANDARD, 25
        ];
        yield 'BR-CO-17 Error #2' => [
            2141.19, 43.91, VatCategory::STANDARD, 2.1
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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null
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
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null
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
            new \DateTimeImmutable('2021-01-02'), null
        ];
        yield 'Invoicing period end date (BT-74) is present' => [
            null, new \DateTimeImmutable('2021-01-03')
        ];
        yield 'Invoicing period start date (BT-73) and Invoicing period end date (BT-74) are present' => [
            new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-03')
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
            new \DateTimeImmutable('2021-01-02'), null
        ];
        yield 'Invoice line period end date (BT-135) is present' => [
            null, new \DateTimeImmutable('2021-01-03')
        ];
        yield 'Invoice line period start date (BT-134) and Invoice line period end date (BT-135) are present' => [
            new \DateTimeImmutable('2021-01-02'), new \DateTimeImmutable('2021-01-03')
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
        $documentLevelAllowance = new DocumentLevelAllowance(14, VatCategory::STANDARD, $reason, $reasonCode);

        $this->assertInstanceOf(DocumentLevelAllowance::class, $documentLevelAllowance);
        $this->assertEquals($reason, $documentLevelAllowance->getReason());
        $this->assertEquals($reasonCode, $documentLevelAllowance->getReasonCode());
    }

    public static function provideBrCo21_success(): \Generator
    {
        yield 'Document level allowance reason (BT-97) is present' => [
            'Reason', null
        ];
        yield 'Document level allowance reason code (BT-98) is present' => [
            null, AllowanceReasonCode::STANDARD
        ];
        yield 'Document level allowance reason (BT-97) and Document level allowance reason code (BT-98) are present' => [
            'Reason', AllowanceReasonCode::STANDARD
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

        new DocumentLevelAllowance(14, VatCategory::STANDARD, $reason, $reasonCode);
    }

    public static function provideBrCo21_error(): \Generator
    {
        yield 'Document level allowance reason (BT-97) as an empty string' => [
            '', null
        ];
        yield 'Document level allowance reason (BT-97) and Document level allowance reason code (BT-98) are null' => [
            null, null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-22 : Each Document level charge (BG-21) shall contain a Document level charge reason (BT-104) or a Document level charge reason code (BT-105), or both.
     * @dataProvider provideBrCo22_success
     */
    public function brCo22_success(?string $reason, ?ChargeReasonCode $reasonCode): void
    {
        $documentLevelCharge = new DocumentLevelCharge(14, VatCategory::STANDARD, $reason, $reasonCode);

        $this->assertInstanceOf(DocumentLevelCharge::class, $documentLevelCharge);
        $this->assertEquals($reason, $documentLevelCharge->getReason());
        $this->assertEquals($reasonCode, $documentLevelCharge->getReasonCode());
    }

    public static function provideBrCo22_success(): \Generator
    {
        yield 'Document level charge reason (BT-104) is present' => [
            'Reason', null
        ];
        yield 'Document level charge reason code (BT-105) is present' => [
            null, ChargeReasonCode::ADVERTISING
        ];
        yield 'Document level charge reason (BT-104) and Document level charge reason code (BT-105) are present' => [
            'Reason', ChargeReasonCode::ADVERTISING
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

        new DocumentLevelCharge(14, VatCategory::STANDARD, $reason, $reasonCode);
    }

    public static function provideBrCo22_error(): \Generator
    {
        yield 'Document level charge reason (BT-104) as an empty string' => [
            '', null
        ];
        yield 'Document level charge reason (BT-104) and Document level charge reason code (BT-105) are null' => [
            null, null
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
            'Reason', null
        ];
        yield 'Invoice line allowance reason code (BT-140) is present' => [
            null, AllowanceReasonCode::STANDARD
        ];
        yield 'Invoice line allowance reason (BT-139) and Invoice line allowance reason code (BT-140) are present' => [
            'Reason', AllowanceReasonCode::STANDARD
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
            '', null
        ];
        yield 'Invoice line allowance reason (BT-139) and Invoice line allowance reason code (BT-140) are null' => [
            null, null
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
            'Reason', null
        ];
        yield 'Invoice line charge reason code (BT-145) is present' => [
            null, ChargeReasonCode::ADVERTISING
        ];
        yield 'Invoice line charge reason (BT-144) and Invoice line charge reason code (BT-145) are present' => [
            'Reason', ChargeReasonCode::ADVERTISING
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
            '', null
        ];
        yield 'Invoice line charge reason (BT-144) and Invoice line charge reason code (BT-145) are null' => [
            null, null
        ];
    }

    /**
     * @test
     * @testdox BR-CO-25 : In case the Amount due for payment (BT-115) is positive, either the Payment due date (BT-9) or the Payment terms (BT-20) shall be present.
     * @dataProvider provideBrCo25_success
     */
    public function brCo25_success(float $amountDueForPayment, ?\DateTimeInterface $paymentDueDate, ?string $paymentTerms): void
    {
        # To validate BR-CO-16
        $invoiceTotalAmountWithVat = $amountDueForPayment;

        # To validate BR-CO-15
        $invoiceTotalAmountWithoutVat = $invoiceTotalAmountWithVat;

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
                null
            ),
            new Buyer('Richard Roe', new BuyerPostalAddress(CountryAlpha2Code::FRANCE)),
            null,
            new DocumentTotals(0, $invoiceTotalAmountWithoutVat, $invoiceTotalAmountWithVat, $amountDueForPayment),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            $paymentDueDate,
            $paymentTerms
        ));

        if ($invoice->getDocumentTotals()->getAmountDueForPayment() > 0) {
            $this->assertTrue($paymentDueDate || $paymentTerms);
        } else {
            $this->assertTrue(true);
        }
    }

    public static function provideBrCo25_success(): \Generator
    {
        yield 'Amount due for payment (BT-115) is positive and the Payment due date (BT-9) is present' => [
            12.2, new \DateTimeImmutable('2021-01-02'), null
        ];

        yield 'Amount due for payment (BT-115) is positive and the Payment terms (BT-20) is present' => [
            12.2, null, '30 JOURS NETS'
        ];

        yield 'Amount due for payment (BT-115) is positive, the Payment due date (BT-9) and the Payment terms (BT-20) are present' => [
            12.2, new \DateTimeImmutable('2021-01-02'), '30 JOURS NETS'
        ];

        yield 'Amount due for payment (BT-115) is negative' => [
            -40, null, null
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
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            $paymentDueDate,
            $paymentTerms
        );
    }

    public static function provideBrCo25_error(): \Generator
    {
        yield 'Amount due for payment (BT-115) is positive, the Payment due date (BT-9) and the Payment terms (BT-20) are not present' => [
            12.2, null, null
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
            new DocumentTotals(0, 0, 0, 0),
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null
        ));

        $seller = $invoice->getSeller();
        $this->assertTrue(!empty($seller->getIdentifiers()) || null !== $seller->getLegalRegistrationIdentifier() || null !== $seller->getVatIdentifier());
    }

    public static function provideBrCo26_success(): \Generator
    {
        yield 'Seller identifier (BT-29)' => [
            [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            null,
            null
        ];
        yield 'Seller legal registration identifier (BT-30)' => [
            [],
            new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            null
        ];
        yield 'Seller VAT identifier (BT-31)' => [
            [],
            null,
            new VatIdentifier('FR88100000009')
        ];
        yield 'Seller identifier (BT-29) and Seller legal registration identifier (BT-30)' => [
            [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            null
        ];
        yield 'Seller identifier (BT-29) and Seller VAT identifier (BT-31)' => [
            [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            null,
            new VatIdentifier('FR88100000009')
        ];
        yield 'Seller legal registration identifier (BT-30) and Seller VAT identifier (BT-31)' => [
            [],
            new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            new VatIdentifier('FR88100000009')
        ];
        yield 'Seller identifier (BT-29) and Seller legal registration identifier (BT-30) and Seller VAT identifier (BT-31)' => [
            [new SellerIdentifier('10000000900017', InternationalCodeDesignator::SIRET_CODE)],
            new LegalRegistrationIdentifier('100000009', InternationalCodeDesignator::SYSTEM_INFORMATION_ET_REPERTOIRE_DES_ENTREPRISE_ET_DES_ETABLISSEMENTS_SIRENE),
            new VatIdentifier('FR88100000009')
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
            [new VatBreakdown(100, 20, VatCategory::STANDARD, 20.00)],
            [new InvoiceLine(
                new InvoiceLineIdentifier("1"),
                1,
                UnitOfMeasurement::BOX_REC21,
                0,
                new PriceDetails(12),
                new LineVatInformation(VatCategory::STANDARD),
                new ItemInformation("A thing"),
            )],
            null,
            null,
            null,
            null
        );
    }

    public static function provideBrCo26_error(): \Generator
    {
        yield 'No field are filled in' => [
            [],
            null,
            null
        ];
    }
}
