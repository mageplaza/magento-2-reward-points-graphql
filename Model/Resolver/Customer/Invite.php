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

use Exception;
use Magento\Customer\Model\Customer;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RewardPoints\Helper\Data;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractReward;

/**
 * Class Invite
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Invite extends AbstractReward
{

    /**
     * @var GetCustomer
     */
    protected $getCustomer;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * Invite constructor.
     *
     * @param GetCustomer $getCustomer
     * @param Data $helperData
     * @param EventManager $eventManager
     */
    public function __construct(
        GetCustomer $getCustomer,
        Data $helperData,
        EventManager $eventManager
    ) {
        $this->getCustomer          = $getCustomer;
        $this->eventManager = $eventManager;

        parent::__construct($helperData);
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isModuleOutputEnabled('Mageplaza_RewardPointsUltimate')) {
            throw new GraphQlInputException(__('Reward Points Ultimate extension is required.'));
        }

        parent::resolve($field, $context, $info, $value, $args);

        /** @var Customer $customer */
        /** @var \Magento\GraphQl\Model\Query\ContextInterface $context
         * \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context class is available < 2.3.3
         */
        $customer = $this->getCustomer->execute($context);

        $inviteObject = new DataObject(['status' => false]);

        try {
            $this->eventManager->dispatch('mp_reward_graphql_invite', [
                    'customer' => $customer,
                    'object'   => $inviteObject,
                    'params'   => $args
                ]
            );
        } catch (Exception $exception) {
            throw new GraphQlInputException(__($exception->getMessage()));
        }

        return $inviteObject->getStatus();
    }
}
