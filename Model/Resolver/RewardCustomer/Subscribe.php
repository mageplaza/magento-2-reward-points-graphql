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

use Magento\Customer\Model\Customer;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RewardPointsUltimate\Helper\Data;
use Mageplaza\RewardPointsUltimate\Model\RewardCustomerRepository;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;

/**
 * Class Subscribe
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\RewardCustomer
 */
class Subscribe extends AbstractReward
{
    /**
     * @var RewardCustomerRepository
     */
    protected $rewardCustomerRepository;

    /**
     * @var GetCustomer
     */
    protected $getCustomer;

    /**
     * Subscribe constructor.
     * @param RewardCustomerRepository $rewardCustomerRepository
     * @param GetCustomer $getCustomer
     * @param Data $helperData
     */
    public function __construct(
        RewardCustomerRepository $rewardCustomerRepository,
        GetCustomer $getCustomer,
        Data $helperData
    ) {
        $this->rewardCustomerRepository = $rewardCustomerRepository;
        $this->getCustomer          = $getCustomer;

        parent::__construct($helperData);
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);

        /** @var Customer $customer */
        /** @var \Magento\GraphQl\Model\Query\ContextInterface $context
         * \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context class is available < 2.3.3
         */
        $customer = $this->getCustomer->execute($context);
        $isUpdate = isset($args['input']['isUpdate']) ? $args['input']['isUpdate'] : false;
        $isExpire = isset($args['input']['isExpire']) ? $args['input']['isExpire'] : false;

        return $this->rewardCustomerRepository->subscribe(
            $customer->getId(),
            $isUpdate,
            $isExpire
        );
    }
}
