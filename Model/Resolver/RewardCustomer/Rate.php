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

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Mageplaza\RewardPoints\Helper\Data;
use Mageplaza\RewardPoints\Model\RewardRateRepository;
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
     * Rate constructor.
     * @param Data $helperData
     * @param RewardRateRepository $rewardRateRepository
     */
    public function __construct(
        Data $helperData,
        RewardRateRepository $rewardRateRepository
    ) {
        $this->rewardRateRepository = $rewardRateRepository;

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

        if (!$this->helperData->isEnabled()) {
            return [];
        }

        $customer = $value['customer'];
        $data = [];
        $earningRate = $this->rewardRateRepository->getRateByCustomer(
            $customer->getGroupId(),
            $customer->getWebsiteId(),
            2
        );
        $pointHelper = $this->helperData->getPointHelper();
        if ($earningRate->getRateId()) {
            $data['earning_rate'] = __(
                'Each %1 spent for your order will earn %2.',
                $this->helperData->convertPrice($earningRate->getMoney(), true, false),
                $pointHelper->format($earningRate->getPoints())
            );
        }

        $spendingRate = $this->rewardRateRepository->getRateByCustomer(
            $customer->getGroupId(),
            $customer->getWebsiteId(),
            1
        );

        if ($spendingRate->getRateId()) {
            $data['spending_rate'] = __(
                'Each %1 can be redeemed for %2.',
                $pointHelper->format($spendingRate->getPoints()),
                $this->helperData->convertPrice($spendingRate->getMoney(), true, false)
            );
        }

        return $data;
    }
}
