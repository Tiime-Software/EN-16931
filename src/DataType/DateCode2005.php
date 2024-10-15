<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

/**
 * @deprecated
 */
enum DateCode2005: string
{
    case INVOICE_DATE = "3";
    case DELIVERY_DATE = "35";
    case PAYMENT_DATE = "432";
}
