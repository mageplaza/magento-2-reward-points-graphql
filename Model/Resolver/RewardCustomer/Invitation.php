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
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Mageplaza\RewardPoints\Helper\Data;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;

/**
 * Class Invitations
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Invitation extends AbstractGetList
{
    /**
     * @var string
     */
    protected $fieldName = 'mp_reward_invitation';

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Invitation constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Data $helperData
     * @param EventManager $eventManager
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Data $helperData,
        EventManager $eventManager
    ) {
        $this->helperData = $helperData;
        $this->eventManager = $eventManager;
        parent::__construct($searchCriteriaBuilder);
    }

    /**
     * @param Customer $customer
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     * @throws GraphQlInputException
     */
    public function getSearchResult($customer, $searchCriteria)
    {
        if (!$this->helperData->isModuleOutputEnabled('Mageplaza_RewardPointsUltimate')) {
            throw new GraphQlInputException(__('Reward Points Ultimate extension is required.'));
        }

        $invitationObject = new DataObject(['invitations' => []]);
        $this->eventManager->dispatch('mp_reward_graphql_get_invitation', [
                'customer_email'  => $customer->getEmail(),
                'search_criteria' => $searchCriteria,
                'object'          => $invitationObject
            ]
        );

        return $invitationObject->getInvitations();
    }
}
