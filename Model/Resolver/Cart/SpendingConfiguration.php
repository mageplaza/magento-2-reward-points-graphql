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

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\Cart;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Mageplaza\RewardPointsUltimate\Helper\Calculation as HelperCalculation;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;

/**
 * Class SpendingConfiguration
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Cart
 */
class SpendingConfiguration extends AbstractReward
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var HelperCalculation
     */
    protected $helperCalculation;

    /**
     * SpendingConfiguration constructor.
     * @param GetCartForUser $getCartForUser
     * @param HelperCalculation $helperCalculation
     */
    public function __construct(
        GetCartForUser $getCartForUser,
        HelperCalculation $helperCalculation
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->helperCalculation = $helperCalculation;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);

        if (empty($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
        $maskedCartId = $args['cart_id'];

        $currentUserId = $context->getUserId();
        $store = $context->getExtensionAttributes()->getStore();
        $storeId = (int)$store->getId();
        $quote = $this->getCartForUser->execute($maskedCartId, $currentUserId, $storeId);
        $this->helperCalculation->setQuote($quote);

        return $this->helperCalculation->getSpendingConfiguration($quote);
    }
}
