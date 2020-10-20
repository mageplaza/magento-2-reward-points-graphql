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

use Magento\Framework\DataObject;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;
use Mageplaza\RewardPoints\Helper\Data;
use Magento\Framework\Event\ManagerInterface as EventManager;

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
     * @var EventManager
     */
    private $eventManager;

    /**
     * SpendingConfiguration constructor.
     *
     * @param Data $helperData
     * @param GetCartForUser $getCartForUser
     * @param EventManager $eventManager
     */
    public function __construct(
        Data $helperData,
        GetCartForUser $getCartForUser,
        EventManager $eventManager
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->eventManager = $eventManager;
        parent::__construct($helperData);
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        if (!$this->helperData->isModuleOutputEnabled('Mageplaza_RewardPointsPro')) {
            throw new GraphQlInputException(__('Reward Points Pro extension is required.'));
        }

        parent::resolve($field, $context, $info, $value, $args);

        if (empty($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }

        if ($this->helperData->versionCompare('2.3.3')) {
            $store = $context->getExtensionAttributes()->getStore();
            $quote = $this->getCartForUser->execute($args['cart_id'], $context->getUserId(), (int)$store->getId());
        } else {
            $quote = $this->getCartForUser->execute($args['cart_id'], $context->getUserId());
        }
        $spendingRuleObject = new DataObject(['rules' => []]);
        $this->eventManager->dispatch('mp_reward_graphql_get_spending_rules', [
                'object' => $spendingRuleObject,
                'quote'  => $quote
            ]
        );

        return $spendingRuleObject->getRules();
    }
}
