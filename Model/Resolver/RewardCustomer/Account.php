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

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\RewardCustomer;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Mageplaza\RewardPointsUltimate\Helper\Crypt;
use Mageplaza\RewardPointsUltimate\Model\RewardCustomerFactory;

/**
 * Class RewardCustomer
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver
 */
class Account implements ResolverInterface
{
    /**
     * @var RewardCustomerFactory
     */
    protected $rewardCustomerFactory;

    /**
     * @var Crypt
     */
    protected $cryptHelper;

    /**
     * RewardCustomer constructor.
     * @param RewardCustomerFactory $rewardCustomerFactory
     * @param Data $helperData
     */
    public function __construct( RewardCustomerFactory $rewardCustomerFactory, Crypt $cryptHelper)
    {
        $this->rewardCustomerFactory = $rewardCustomerFactory;
        $this->cryptHelper = $cryptHelper;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $customer = $value['model'];

        $rewardCustomer = $this->rewardCustomerFactory->create()->loadByCustomerId($customer->getId());
        $data = $rewardCustomer->toArray();
        $data['point_spent'] = $rewardCustomer->getPointSpent();
        $data['point_earned'] = $rewardCustomer->getPointEarned();
        $data['refer_code'] = $this->cryptHelper->encrypt($customer->getId());

        return $data;
    }
}
