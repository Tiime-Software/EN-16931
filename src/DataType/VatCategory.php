<?php

namespace Tiime\EN16931\DataType;

enum VatCategory: string
{
    case VAT_REVERSE_CHARGE = "AE";
    case EXEMPT_FROM_TAX = "E";
    case FREE_EXPORT_ITEM_TAX_NOT_CHARGED = "G";
    case VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES = "K";
    case CANARY_ISLANDS = "L";
    case CEUTA_AND_MELILLA = "M";
    case SERVICE_OUTSIDE_SCOPE_OF_TAX = "O";
    case STANDARD = "S";
    case ZERO_RATED_GOODS = "Z";
}
