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

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\Configs;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RewardPoints\Helper\Data;
use Mageplaza\RewardPoints\Model\ConfigRepository;

/**
 * Class Get
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Configs
 */
class Get implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * Get constructor.
     *
     * @param Data $helperData
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        Data $helperData,
        ConfigRepository $configRepository
    ) {
        $this->helperData       = $helperData;
        $this->configRepository = $configRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            throw new GraphQlInputException(__('Reward points is disabled.'));
        }

        $store   = $context->getExtensionAttributes()->getStore();
        $storeId = $store->getId();
        $storeConfigs = $this->configRepository->getStoreConfigs($storeId);
        $storeConfigs['earning']['earn_from_tax'] = $storeConfigs['earning']['earn_from'];

        return $storeConfigs;
    }
}
