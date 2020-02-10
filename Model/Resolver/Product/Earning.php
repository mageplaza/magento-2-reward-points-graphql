<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Mageplaza\RewardPointsGraphQl\Model\Resolver\Product;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Mageplaza\RewardPoints\Helper\Point;
use Mageplaza\RewardPointsPro\Model\CatalogRuleFactory;
use Magento\Catalog\Model\Product;

/**
 * Class Earning
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Product
 */
class Earning implements ResolverInterface
{
    /**
     * @var CatalogRuleFactory
     */
    protected $catalogRule;

    /**
     * @var Point
     */
    protected $pointHelper;

    /**
     * ProductRepository constructor.
     *
     * @param CatalogRuleFactory $catalogRuleFactory
     * @param Point $pointHelper
     */
    public function __construct(
        CatalogRuleFactory $catalogRuleFactory,
        Point $pointHelper
    ){
        $this->catalogRule = $catalogRuleFactory;
        $this->pointHelper = $pointHelper;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $product = $value['model'];
        $pointEarn = $this->catalogRule->create()->getPointEarnFromRules($product);

        return [
            'earning_point' => $pointEarn,
            'earning_point_format' => $this->pointHelper->format($pointEarn)
        ];
    }
}
