<?php

declare(strict_types=1);

namespace Orienteed\GraphQl\Plugin\Model\Resolver;

use Magento\Checkout\Api\PaymentInformationManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Get list of active payment methods resolver.
 */
class AvailablePaymentMethods extends \Magento\QuoteGraphQl\Model\Resolver\AvailablePaymentMethods
{
    /**
     * @param PaymentInformationManagementInterface $informationManagement
     */
    public function __construct(
        PaymentInformationManagementInterface $informationManagement,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
        $this->informationManagement = $informationManagement;
        parent::__construct(
            $informationManagement
        );
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        // Custom Start
        if ($currentUserId = $context->getUserId()) {
            $this->customerSession->setCustomerId($currentUserId);
        }
        // Custom End

        $cart = $value['model'];
        return $this->getPaymentMethodsData($cart);
    }

    /**
     * Collect and return information about available payment methods
     *
     * @param CartInterface $cart
     * @return array
     */
    private function getPaymentMethodsData(CartInterface $cart): array
    {
        $paymentInformation = $this->informationManagement->getPaymentInformation($cart->getId());
        $paymentMethods = $paymentInformation->getPaymentMethods();

        $paymentMethodsData = [];
        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethodsData[] = [
                'title' => $paymentMethod->getTitle(),
                'code' => $paymentMethod->getCode(),
            ];
        }
        return $paymentMethodsData;
    }
}
