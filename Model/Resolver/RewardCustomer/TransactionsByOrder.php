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

use Exception;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Model\Order;
use Mageplaza\RewardPointsGraphQl\Model\Resolver\AbstractGetList;
use Mageplaza\RewardPoints\Model\TransactionRepository;

/**
 * Class TransactionsByOrder
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\RewardCustomer
 */
class TransactionsByOrder extends AbstractGetList
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
     * TransactionsByOrder constructor.
     *
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
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array
     * @throws GraphQlInputException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            return [];
        }

        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }

        try {
            /** @var Order $order */
            $order          = $value['model'];
            $searchCriteria = $this->searchCriteriaBuilder->build($this->fieldName, $args);
            $searchCriteria->setCurrentPage($args['currentPage']);
            $searchCriteria->setPageSize($args['pageSize']);
            $searchResult = $this->getSearchResult($order->getId(), $searchCriteria);

            return [
                'total_count' => $searchResult->getTotalCount(),
                'items'       => $searchResult->getItems(),
                'page_info'   => $this->getPageInfo($searchResult, $args)
            ];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @param int $orderId
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return mixed
     * @throws LocalizedException
     */
    public function getSearchResult($orderId, $searchCriteria)
    {
        $result = $this->transactionRepository->getListByOrderId($searchCriteria, $orderId);
        foreach ($result->getItems() as $item) {
            $item->setComment($item->getTitle());
        }

        return $result;
    }
}
