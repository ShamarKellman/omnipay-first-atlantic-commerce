<?php

namespace Omnipay\FirstAtlanticCommerce\Enums;

use DASPRiD\Enum\AbstractEnum;

/**
 * Class ModificationType
 * @method static self CAPTURE()
 * @method static self REFUND()
 * @method static self REVERSAL()
 * @method static self CANCEL_RECURRING()
 */
final class ModificationType extends AbstractEnum
{
    public const CAPTURE = 1;
    public const REFUND = 2;
    public const REVERSAL = 3;
    public const CANCEL_RECURRING = 4;
}
