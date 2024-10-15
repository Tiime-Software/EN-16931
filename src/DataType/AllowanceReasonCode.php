<?php

declare(strict_types=1);

namespace Tiime\EN16931\DataType;

/**
 * UNTDID 5189 - Allowance codes (BT-140 / BT-98)
 * Published by France (31/07/2023)
 *
 * @deprecated
 */
enum AllowanceReasonCode: string
{
    /**
     * Bonus for completing work ahead of schedule.
     */
    case BONUS_FOR_WORKS_AHEAD_OF_SCHEDULE = '41';

    /**
     * Bonus earned for other reasons.
     */
    case OTHER_BONUS = '42';

    /**
     * A discount given by the manufacturer which should be passed on to the consumer.
     */
    case MANUFACTURER_CONSUMER_DISCOUNT = '60';

    /**
     * Allowance granted because of the military status.
     */
    case DUE_TO_MILITARY_STATUS = '62';

    /**
     * Allowance granted to a victim of a work accident.
     */
    case DUE_TO_WORK_ACCIDENT = '63';

    /**
     * An allowance or charge as specified in a special agreement.
     */
    case SPECIAL_AGREEMENT = '64';

    /**
     * A discount given for the purchase of a product with a production error.
     */
    case PRODUCTION_ERROR_DISCOUNT  = '65';

    /**
     * A discount given at the occasion of the opening of a new outlet.
     */
    case NEW_OUTLET_DISCOUNT = '66';

    /**
     * A discount given for the purchase of a sample of a product.
     */
    case SAMPLE_DISCOUNT = '67';

    /**
     * A discount given for the purchase of an end-of-range product.
     */
    case END_OF_RANGE_DISCOUNT = '68';

    /**
     * A discount given for a specified Incoterm.
     */
    case INCOTERM_DISCOUNT = '70';

    /**
     * Allowance for reaching or exceeding an agreed sales threshold at the point of sales.
     */
    case POINT_OF_SALES_THRESHOLD_ALLOWANCE = '71';

    /**
     * Surcharge/deduction, calculated for higher/lower material's consumption.
     */
    case MATERIAL_SURCHARGE_DEDUCTION = '88';

    /**
     * A reduction from a usual or list price.
     */
    case DISCOUNT = '95';

    /**
     * A return of part of an amount paid for goods or services, serving as a reduction or discount.
     */
    case SPECIAL_REBATE = '100';

    /**
     * A fixed long term allowance or charge.
     */
    case FIXED_LONG_TERM = '102';

    /**
     * A temporary allowance or charge.
     */
    case TEMPORARY = '103';

    /**
     * The standard available allowance or charge.
     */
    case STANDARD = '104';

    /**
     * An allowance or charge based on yearly turnover.
     */
    case YEARLY_TURNOVER = '105';
}
