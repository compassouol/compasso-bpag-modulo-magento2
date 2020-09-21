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

use Magento\Payment\Helper\Data as PaymentHelper;

class PaymentConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{

    const PAYMENT_METHOD_COMPASSO_UOL_PAGAMENTOS_CODE = 'compasso_uol_pagamentos_credit_card';

    private $method;

    /**
     * PaymentConfigProvider constructor.
     * @param PaymentHelper $helper
     */
    public function __construct(PaymentHelper $helper)
    {
        $this->method = $helper->getMethodInstance(self::PAYMENT_METHOD_COMPASSO_UOL_PAGAMENTOS_CODE);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_library = $objectManager->create('\Compasso\Bpag\Helper\Library');
    }

    /**
     * Get payment config
     *
     * @return array
     */
    public function getConfig()
    {
        $this->_library->setEnvironment();
        
        $config = [
            'library' => [
                'session' => $this->_library->getSession()
            ],
            'brazilFlagPath' => $this->_library->getImageUrl('Compasso_Bpag::images/flag-origin-country.png'),
            'compassoPayment' => $this->method->getStandardCheckoutPaymentUrl(),
        ];

        //echo '<pre>',print_r($config),'</pre>';
        return $config;
    }
}
