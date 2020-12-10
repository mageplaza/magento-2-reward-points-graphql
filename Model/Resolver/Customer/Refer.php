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
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Mageplaza\RewardPoints\Helper\Data;

/**
 * Class Refer
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Refer implements ResolverInterface
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Refer constructor.
     *
     * @param EventManager $eventManager
     * @param Data $helperData
     */
    public function __construct(
        EventManager $eventManager,
        Data $helperData
    ) {
        $this->eventManager = $eventManager;
        $this->helperData   = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isModuleOutputEnabled('Mageplaza_RewardPointsUltimate')) {
            throw new GraphQlInputException(__('Reward Points Ultimate extension is required.'));
        }

        $referObject = new DataObject(['status' => false]);

        try {
            $this->eventManager->dispatch('mp_reward_graphql_refer', [
                    'refer_code' => $args['refer_code'],
                    'object'     => $referObject
                ]
            );
        } catch (Exception $exception) {
            throw new GraphQlInputException(__($exception->getMessage()));
        }

        return $referObject->getStatus();
    }
}
