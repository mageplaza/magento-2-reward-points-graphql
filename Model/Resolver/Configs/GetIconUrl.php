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

use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Mageplaza\Core\Helper\Media;
use Mageplaza\RewardPoints\Helper\Data;

/**
 * Class GetIconUrl
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Configs
 */
class GetIconUrl implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var AssetRepository
     */
    protected $assetRepo;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Media
     */
    protected $mediaHelper;

    /**
     * GetIconUrl constructor.
     *
     * @param Data $helperData
     * @param AssetRepository $assetRepo
     * @param RequestInterface $request
     * @param Media $media
     */
    public function __construct(
        Data $helperData,
        AssetRepository $assetRepo,
        RequestInterface $request,
        Media $media
    ){
        $this->helperData  = $helperData;
        $this->assetRepo   = $assetRepo;
        $this->request     = $request;
        $this->mediaHelper = $media;
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

        if (!$this->helperData->getConfigGeneral('show_point_icon', $storeId)) {
            return '';
        }

        $icon = $this->helperData->getConfigGeneral('icon', $storeId);
        if ($icon && $this->mediaHelper->getMediaDirectory()->isExist('mageplaza/rewardpoints/' . $icon)) {
            $iconUrl = $this->mediaHelper->getMediaUrl('mageplaza/rewardpoints/' . $icon);
        } else {
            $iconUrl = $this->assetRepo->getUrlWithParams(
                'Mageplaza_RewardPoints::images/default/point.png',
                ['_secure' => $this->request->isSecure(), 'area' => Area::AREA_FRONTEND]
            );
        }

        return [
            'url' => $iconUrl
        ];
    }
}
