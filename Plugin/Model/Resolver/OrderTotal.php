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

namespace Mageplaza\RewardPointsGraphQl\Plugin\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Magento\SalesGraphQl\Model\Resolver\OrderTotal as SalesGraphQlOrderTotal;
use Mageplaza\RewardPoints\Helper\Data;

/**
 * Class OrderTotal
 * @package Mageplaza\RewardPointsGraphQl\Plugin\Model\Resolver
 */
class OrderTotal
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * OrderTotal constructor.
     *
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * @param SalesGraphQlOrderTotal $subject
     * @param array $result
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function afterResolve(SalesGraphQlOrderTotal $subject, $result)
    {
        if (!$this->helperData->isEnabled()) {
            return $result;
        }

        if (!(($result['model'] ?? null) instanceof OrderInterface)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Order $order */
        $order                      = $result['model'];
        $result['mp_reward_points'] = [
            'earn'     => $order->getData('mp_reward_earn'),
            'spent'    => $order->getData('mp_reward_spent'),
            'discount' => abs($order->getData('mp_reward_discount'))
        ];

        return $result;
    }
}
