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

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Mageplaza\RewardPointsUltimate\Helper\Data;
use Mageplaza\RewardPointsUltimate\Model\RewardRateRepository;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;

/**
 * Class Rate
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\RewardCustomer
 */
class Rate extends AbstractReward
{
    /**
     * @var RewardRateRepository
     */
    protected $rewardRateRepository;

    /**
     * @var GetCustomer
     */
    protected $getCustomer;

    /**
     * Rate constructor.
     * @param Data $helperData
     * @param RewardRateRepository $rewardRateRepository
     * @param GetCustomer $getCustomer
     */
    public function __construct(
        Data $helperData,
        RewardRateRepository $rewardRateRepository,
        GetCustomer $getCustomer
    ) {
        $this->rewardRateRepository = $rewardRateRepository;
        $this->getCustomer          = $getCustomer;

        parent::__construct($helperData);
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

        parent::resolve($field, $context, $info, $value, $args);

        /** @var \Magento\GraphQl\Model\Query\ContextInterface $context
         * \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context class is available < 2.3.3
         */
        $customer  = $this->getCustomer->execute($context);
        $direction = $args['direction'];

        return $this->rewardRateRepository->getRateByCustomer(
            $customer->getGroupId(),
            $customer->getWebsiteId(),
            $direction
        )->toArray();
    }
}
