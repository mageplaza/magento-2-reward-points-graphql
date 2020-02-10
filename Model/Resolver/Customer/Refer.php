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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Mageplaza\RewardPointsUltimate\Model\InvitationRepository;

/**
 * Class Refer
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Refer implements ResolverInterface
{
    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * Refer constructor.
     * @param InvitationRepository $invitationRepository
     */
    public function __construct(
        InvitationRepository $invitationRepository
    ) {
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return $this->invitationRepository->referByCode($args['refer_code']);
    }
}
