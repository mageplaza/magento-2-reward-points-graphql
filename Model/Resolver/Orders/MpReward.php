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

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\Orders;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Mageplaza\RewardPoints\Helper\Data;

/**
 * Class MpReward
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Orders
 */
class MpReward implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * MpReward constructor.
     *
     * @param Data $helperData
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        Data $helperData,
        OrderFactory $orderFactory
    ) {
        $this->helperData   = $helperData;
        $this->orderFactory = $orderFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            return null;
        }

        try {
            if (isset($value['increment_id'])) {
                $order = $this->orderFactory->create()->loadByIncrementId($value['increment_id']);

                return [
                    'earn'     => $order->getData('mp_reward_earn'),
                    'spent'    => $order->getData('mp_reward_spent'),
                    'discount' => abs($order->getData('mp_reward_discount'))
                ];
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }
}
