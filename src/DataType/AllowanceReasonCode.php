<?php

namespace Tiime\EN16931\DataType;

enum AllowanceReasonCode: string
{
    case BONUS_FOR_WORKS_AHEAD_OF_SCHEDULE = "41";
    case OTHER_BONUS = "42";
    case MANUFACTURER_CONSUMER_DISCOUNT = "60";
    case DUE_TO_MILITARY_STATUS = "62";
    case DUE_TO_WORK_ACCIDENT = "63";
    case SPECIAL_AGREEMENT = "64";
    case PRODUCTION_ERROR_DISCOUNT = "65";
    case NEW_OUTLET_DISCOUNT = "66";
    case SAMPLE_DISCOUNT = "67";
    case END_OF_RANGE_DISCOUNT = "68";
    case INCOTERM_DISCOUNT = "70";
    case POINT_OF_SALES_THRESHOLD_ALLOWANCE = "71";
    case MATERIAL_SURCHARGE_DEDUCTION = "88";
    case DISCOUNT = "95";
    case SPECIAL_REBATE = "100";
    case FIXED_LONG_TERM = "102";
    case TEMPORARY = "103";
    case STANDARD = "104";
    case YEARLY_TURNOVER = "105";
}
