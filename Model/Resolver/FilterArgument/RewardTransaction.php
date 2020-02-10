<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_RewardPointsGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */


declare(strict_types=1);

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\FilterArgument;

use Mageplaza\RewardPointsGraphQl\Model\Resolver\FilterArgument;

/**
 * Class RewardTransaction
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\FilterArgument
 */
class RewardTransaction extends FilterArgument
{
    protected $type = 'MpRewardTransactions';
}
