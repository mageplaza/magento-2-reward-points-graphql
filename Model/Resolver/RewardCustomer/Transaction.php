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

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\GraphQl\Model\Query\ContextInterface;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Mageplaza\RewardPointsUltimate\Api\Data\TransactionSearchResultInterface;
use Mageplaza\RewardPointsUltimate\Model\TransactionRepository;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface as ResolverContextInterface;

/**
 * Class Transaction
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\RewardCustomer
 */
class Transaction extends AbstractGetList
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
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * Customer constructor.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GetCustomer $getCustomer
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GetCustomer $getCustomer,
        TransactionRepository $transactionRepository
    ) {
        $this->getCustomer = $getCustomer;
        $this->transactionRepository = $transactionRepository;
        parent::__construct($searchCriteriaBuilder);
    }

    /**
     * @param ResolverContextInterface $context
     * @param SearchCriteriaInterface $searchCriteria
     * @return TransactionSearchResultInterface|mixed
     * @throws GraphQlAuthenticationException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     */
    public function getSearchResult($context, $searchCriteria)
    {
        /** @var ContextInterface $context */
        $customer = $this->getCustomer->execute($context);

        return $this->transactionRepository->getListByCustomerId($searchCriteria, $customer->getId());
    }
}