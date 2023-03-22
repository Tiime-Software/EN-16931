<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

enum DateCode2005: string
{
    case INVOICE_DATE_TIME = "3";
    case DELIVERY_DATE_TIME = "35";
    case PAID_TO_DATE = "432";
}
