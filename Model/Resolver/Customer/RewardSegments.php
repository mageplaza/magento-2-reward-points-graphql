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

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\CartTotalRepositoryInterface;

/**
 * Class RewardSegments
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class RewardSegments implements ResolverInterface
{
    /**
     * Cart total repository.
     *
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalRepository;

    /**
     * RewardSegments constructor.
     *
     * @param CartTotalRepositoryInterface $cartTotalRepository
     */
    public function __construct(CartTotalRepositoryInterface $cartTotalRepository)
    {
        $this->cartTotalRepository = $cartTotalRepository;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array[]
     * @throws GraphQlNoSuchEntityException
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Quote $quote */
        $quote = $value['model'];
        $totals = $this->cartTotalRepository->get($quote->getId());

        $rewardSegments = [];
        foreach ($totals->getTotalSegments() as $segment) {
            if (in_array($segment->getCode(), ['mp_reward_earn', 'mp_reward_discount', 'mp_reward_spent'],true)) {
                $rewardSegments[] = $segment;
            }
        }

        return $rewardSegments;
    }
}