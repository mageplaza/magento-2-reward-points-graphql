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
use Magento\GraphQl\Model\Query\ContextInterface;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Mageplaza\RewardPointsUltimate\Api\Data\InvitationSearchResultInterface;
use Mageplaza\RewardPointsUltimate\Model\InvitationRepository;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface as ResolverContextInterface;

/**
 * Class Invitations
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Invitation extends AbstractGetList
{
    /**
     * @var string
     */
    protected $fieldName = 'mp_reward_transactions';

    /**
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * Invitations constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GetCustomer $getCustomer
     * @param InvitationRepository $invitationRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GetCustomer $getCustomer,
        InvitationRepository $invitationRepository
    ) {
        $this->getCustomer          = $getCustomer;
        $this->invitationRepository = $invitationRepository;
        parent::__construct($searchCriteriaBuilder);
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
        /** @var ContextInterface $context */
        $customer = $this->getCustomer->execute($context);

        return $this->invitationRepository->getReferralByEmail($customer->getEmail());
    }
}
