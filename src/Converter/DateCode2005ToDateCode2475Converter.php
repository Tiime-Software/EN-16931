<?php

declare(strict_types=1);

namespace Tiime\EN16931\Converter;

use Tiime\EN16931\DataType\DateCode2005;
use Tiime\EN16931\DataType\DateCode2475;

/**
 * @deprecated
 */
class DateCode2005ToDateCode2475Converter
{
    public static function convert(DateCode2005 $dateCode2005): DateCode2475
    {
        return match ($dateCode2005) {
            DateCode2005::INVOICE_DATE => DateCode2475::INVOICE_DATE,
            DateCode2005::DELIVERY_DATE => DateCode2475::DELIVERY_DATE,
            DateCode2005::PAYMENT_DATE => DateCode2475::PAYMENT_DATE
        };
    }
}
