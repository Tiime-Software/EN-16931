<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

enum DateCode2475: string
{
    case INVOICE_DATE = "5";
    case DELIVERY_DATE = "29";
    case PAYMENT_DATE = "72";
}
