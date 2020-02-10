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

use Magento\Customer\Model\Customer;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\GraphQl\Model\Query\ContextInterface;
use Mageplaza\RewardPointsUltimate\Model\InvitationRepository;

/**
 * Class Invite
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class Invite implements ResolverInterface
{
    /**
     * @var InvitationRepository
     */
    protected $invitationRepository;

    /**
     * @var GetCustomer
     */
    protected $getCustomer;

    /**
     * Invite constructor.
     *
     * @param InvitationRepository $invitationRepository
     * @param GetCustomer $getCustomer
     */
    public function __construct(
        InvitationRepository $invitationRepository,
        GetCustomer $getCustomer
    ) {
        $this->invitationRepository = $invitationRepository;
        $this->getCustomer          = $getCustomer;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /** @var ContextInterface $context */
        /** @var Customer $customer */
        $customer = $this->getCustomer->execute($context);

        return $this->invitationRepository->sendInvitation(
            $customer,
            $args['send_from'],
            $args['emails'],
            $args['message']
        );
    }
}
