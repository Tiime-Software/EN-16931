<?php

declare(strict_types=1);

namespace Tiime\EN16931\Converter;

use Tiime\EN16931\Codelist\TimeReferencingCodeUNTDID2005;
use Tiime\EN16931\Codelist\TimeReferencingCodeUNTDID2475;

final readonly class TimeReferencingCodeUNTDID2005ToTimeReferencingCodeUNTDID2475
{
    public static function convertToUNTDID2475(TimeReferencingCodeUNTDID2005 $code): TimeReferencingCodeUNTDID2475
    {
        return match ($code) {
            TimeReferencingCodeUNTDID2005::INVOICE_DOCUMENT_ISSUE_DATE_TIME => TimeReferencingCodeUNTDID2475::DATE_OF_INVOICE,
            TimeReferencingCodeUNTDID2005::DELIVERY_DATE_TIME_ACTUAL => TimeReferencingCodeUNTDID2475::DATE_OF_DELIVERY_OF_GOODS_TO_ESTABLISHMENTS_DOMICILE_SITE,
            TimeReferencingCodeUNTDID2005::PAID_TO_DATE => TimeReferencingCodeUNTDID2475::PAYMENT_DATE
        };
    }

    public static function convertToUNTDID2005(TimeReferencingCodeUNTDID2475 $code): TimeReferencingCodeUNTDID2005
    {
        return match ($code) {
            TimeReferencingCodeUNTDID2475::DATE_OF_INVOICE => TimeReferencingCodeUNTDID2005::INVOICE_DOCUMENT_ISSUE_DATE_TIME,
            TimeReferencingCodeUNTDID2475::DATE_OF_DELIVERY_OF_GOODS_TO_ESTABLISHMENTS_DOMICILE_SITE => TimeReferencingCodeUNTDID2005::DELIVERY_DATE_TIME_ACTUAL,
            TimeReferencingCodeUNTDID2475::PAYMENT_DATE => TimeReferencingCodeUNTDID2005::PAID_TO_DATE
        };
    }
}
