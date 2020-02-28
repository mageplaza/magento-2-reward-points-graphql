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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Magento\QuoteGraphQl\Model\Cart\QuoteAddressFactory;
use Mageplaza\RewardPoints\Model\Api\SpendingManagement;
use Magento\Checkout\Model\TotalsInformation;
use Mageplaza\RewardPointsUltimate\Helper\Data;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;

/**
 * Class SpendingPoint
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class SpendingPoint extends AbstractReward
{
    /**
     * @var SpendingManagement
     */
    protected $spendingManagement;

    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var QuoteAddressFactory
     */
    private $quoteAddressFactory;

    /**
     * @var TotalsInformation
     */
    protected $totalInformation;

    /**
     * SpendingPoint constructor.
     * @param SpendingManagement $spendingManagement
     * @param GetCartForUser $getCartForUser
     * @param QuoteAddressFactory $quoteAddressFactory
     * @param TotalsInformation $totalInformation
     * @param Data $helperData
     */
    public function __construct(
        SpendingManagement $spendingManagement,
        GetCartForUser $getCartForUser,
        QuoteAddressFactory $quoteAddressFactory,
        TotalsInformation $totalInformation,
        Data $helperData
    ) {
        $this->quoteAddressFactory = $quoteAddressFactory;
        $this->spendingManagement  = $spendingManagement;
        $this->getCartForUser      = $getCartForUser;
        $this->totalInformation    = $totalInformation;
        parent::__construct($helperData);
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);

        if ($this->helperData->versionCompare('2.3.3')) {
            $store = $context->getExtensionAttributes()->getStore();
            $quote = $this->getCartForUser->execute($args['cart_id'], $context->getUserId(), (int)$store->getId());
        } else {
            $quote = $this->getCartForUser->execute($args['cart_id'], $context->getUserId());
        }

        $addressInput    = $args['address_information']['address'];
        $shippingMethods = $args['address_information']['shipping_methods'];
        $quoteAddress    = $this->quoteAddressFactory->createBasedOnInputData($addressInput);
        $this->totalInformation->setAddress($quoteAddress);
        $this->totalInformation->setShippingCarrierCode($shippingMethods['carrier_code']);
        $this->totalInformation->setShippingMethodCode($shippingMethods['method_code']);

        $totals = $this->spendingManagement->calculate(
            $quote->getId(),
            $this->totalInformation,
            $args['points'],
            $args['rule_id']
        );

        return $totals->getTotalSegments();
    }
}
