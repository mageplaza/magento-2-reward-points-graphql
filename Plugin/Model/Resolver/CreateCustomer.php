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

namespace Mageplaza\RewardPointsGraphQl\Plugin\Model\Resolver;

use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\CustomerGraphQl\Model\Resolver\CreateCustomer as CreateCustomerAbstract;
use Magento\Framework\Exception\InputException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Mageplaza\RewardPointsUltimate\Helper\Data as ultimateData;

/**
 * Class CreateCustomer
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver
 */
class CreateCustomer
{
    /**
     * @var ultimateData
     */
    protected $ultimateData;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * CreateCustomer constructor.
     *
     * @param ultimateData $ultimateData
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        ultimateData $ultimateData,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->ultimateData       = $ultimateData;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param CreateCustomerAbstract $subject
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array
     * @throws InputException
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     */
    public function beforeResolve(
        CreateCustomerAbstract $subject,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        if (isset($args['input']['mp_refer'])) {
            try {
                $referCodeOrEmail = trim($args['input']['mp_refer']);
                $referCode        = $this->ultimateData->getCryptHelper()->checkReferCodeOrEmail($referCodeOrEmail);
            } catch (Exception $e) {
                $referCode = false;
            }

            if ($referCode) {
                $this->ultimateData->getCookieHelper()->set($referCode);
            } else {
                $this->ultimateData->getCookieHelper()->deleteMpRefererKeyFromCookie();
            }
        }

        return [$field, $context, $info, $value, $args];
    }
}
