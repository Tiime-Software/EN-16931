<?php

declare(strict_types=1);

namespace Tiime\EN16931\Tests\Helper;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tiime\EN16931\Codelist\InvoiceTypeCodeUNTDID1001;
use Tiime\EN16931\Helper\InvoiceTypeCodeUNTDID1001Helper;

final class InvoiceTypeCodeUNTDID1001HelperTest extends TestCase
{
    /**
     * Codes de l'extension française (annexe 7 PPF, onglet codelists), absents de la codelist EN 16931 v14.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function frenchCodesProvider(): iterable
    {
        yield '471' => ['471', true];
        yield '472' => ['472', true];
        yield '473' => ['473', true];
        yield '500' => ['500', true];
        yield '501' => ['501', true];
        yield '502' => ['502', false];
        yield '503' => ['503', false];
    }

    #[DataProvider('frenchCodesProvider')]
    public function testFrenchCodesAreClassified(string $code, bool $isInvoice): void
    {
        $typeCode = InvoiceTypeCodeUNTDID1001::from($code);

        self::assertSame($isInvoice, InvoiceTypeCodeUNTDID1001Helper::isInvoice($typeCode));
    }
}
