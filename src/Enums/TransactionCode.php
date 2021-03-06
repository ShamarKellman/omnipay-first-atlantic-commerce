<?php

namespace Omnipay\FirstAtlanticCommerce\Enums;

use DASPRiD\Enum\AbstractEnum;

/**
 * Class TransactionCode
 * @method static self NONE()
 * @method static self AVS_CHECK()
 * @method static self HOST_SPECIFIC_AVS_CHECK()
 * @method static self PREVIOUS_3DS()
 * @method static self SINGLE_PASS()
 * @method static self THREE_DS_ONLY()
 * @method static self REQUEST_TOKEN()
 * @method static self HOSTED_PAGE_3DS()
 * @method static self FRAUD_CHECK_ONLY()
 * @method static self FRAUD_TEST()
 * @method static self SUBSEQUENT_RECURRING()
 * @method static self INITIAL_RECURRING()
 * @method static self INITIAL_RECURRING_FREE_TRIALS()
 */
final class TransactionCode extends AbstractEnum
{
    public const NONE = 0;
    public const AVS_CHECK = 1;
    public const HOST_SPECIFIC_AVS_CHECK = 2;
    public const PREVIOUS_3DS = 4;
    public const SINGLE_PASS = 8;
    public const THREE_DS_ONLY = 64;
    public const REQUEST_TOKEN = 128;
    public const HOSTED_PAGE_3DS = 256;
    public const FRAUD_CHECK_ONLY = 512;
    public const FRAUD_TEST = 1024;
    public const SUBSEQUENT_RECURRING = 2048;
    public const INITIAL_RECURRING = 4096;
    public const INITIAL_RECURRING_FREE_TRIALS = 8192;
}
