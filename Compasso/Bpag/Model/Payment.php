<?php
/**
 * 2019-20 [Compasso UOL Tecnologia]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    Compasso UOL Tecnologia
 *  @copyright 2020 Compasso UOL Tecnologia
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Compasso\Bpag\Model;

use Magento\Sales\Model\Order\Payment as PaymentOrder;

/**
 * Class Payment
 * @package Compasso\Bpag\Model
 */
class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     *
     */
    const PAYMENT_METHOD_COMPASSO_UOL_PAGAMENTOS_CODE = 'compasso_uol_pagamentos_credit_card';
    /**
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_COMPASSO_UOL_PAGAMENTOS_CODE;
    /**
     * @var bool
     */
    protected $_isGateway = true;
    /**
     * @var bool
     */
    protected $_canCapture = true;
    /**
     * @var bool
     */
    protected $_canUseForMultishipping = true;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    private $cart;

    /**
     * Payment constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Compasso\Bpag\Helper\Library $helper
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $attributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Checkout\Model\Cart $cart
    ) {

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $attributeFactory,
            $paymentData,
            $scopeConfig,
            $logger
        );
        /** @var \Magento\Checkout\Model\Cart _cart */
        $this->_cart = $cart;
    }

    /**
     * Check if checkout type is direct
     *
     * @return bool
     */
    public function isDirectCheckout()
    {
        return true;
    }

    /**
     * Get direct checkout payment url
     *
     * @return url
     */
    public function getDirectCheckoutPaymentUrl()
    {
        return $this->_cart->getQuote()->getStore()->getUrl("bpag/direct/payment");
    }
    
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null){
        return false
        && parent::isAvailable($quote);
    }
}
