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

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Mageplaza\RewardPointsUltimate\Api\Data\InvitationSearchResultInterface;
use Mageplaza\RewardPointsUltimate\Model\InvitationRepository;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface as ResolverContextInterface;
use Mageplaza\RewardPointsUltimate\Helper\Data;

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
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * Invitation constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Data $helperData
     * @param GetCustomer $getCustomer
     * @param InvitationRepository $invitationRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Data $helperData,
        GetCustomer $getCustomer,
        InvitationRepository $invitationRepository
    ) {
        $this->getCustomer          = $getCustomer;
        $this->invitationRepository = $invitationRepository;
        parent::__construct($searchCriteriaBuilder, $helperData);
    }

    /**
     * @param ResolverContextInterface $context
     * @param SearchCriteriaInterface $searchCriteria
     * @return InvitationSearchResultInterface|mixed
     * @throws GraphQlAuthenticationException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function getSearchResult($context, $searchCriteria)
    {
        /** @var \Magento\GraphQl\Model\Query\ContextInterface $context
         * \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context class is available < 2.3.3
         */
        $customer = $this->getCustomer->execute($context);

        return $this->invitationRepository->getReferralByEmail($searchCriteria, $customer->getEmail());
    }
}
