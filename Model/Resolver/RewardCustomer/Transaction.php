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
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;
use Mageplaza\RewardPointsUltimate\Api\Data\TransactionSearchResultInterface;
use Mageplaza\RewardPointsUltimate\Model\TransactionRepository;

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
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * Transaction constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TransactionRepository $transactionRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        parent::__construct($searchCriteriaBuilder);
    }

    /**
     * @param Customer $customer
     * @param SearchCriteriaInterface $searchCriteria
     * @return TransactionSearchResultInterface|mixed
     */
    public function getSearchResult($customer, $searchCriteria)
    {
        $result =  $this->transactionRepository->getListByCustomerId($searchCriteria, $customer->getId());
        foreach ($result->getItems() as $item) {
            $item->setComment($item->getTitle());
        }

        return $result;
    }
}
