<?php
/**
 * 2007-2016 [PagSeguro Internet Ltda.]
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
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2016 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Compasso\Bpag\Controller\Adminhtml\Abandoned;

use Compasso\Bpag\Controller\Ajaxable;
use Compasso\Bpag\Model\Transactions\Methods\Abandoned;

/**
 * Class Conciliation
 * @package Compasso\Bpag\Controller\Adminhtml
 */
class Transport extends Ajaxable
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context, $resultJsonFactory);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $abandoned = new Abandoned(
            $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface'),
            $this->_objectManager->create('Magento\Framework\App\ResourceConnection'),
            $this->_objectManager->create('Magento\Framework\Mail\Template\TransportBuilder'),
            $this->_objectManager->create('Magento\Framework\Model\ResourceModel\Db\Context'),
            $this->_objectManager->create('Magento\Backend\Model\Session'),
            $this->_objectManager->create('Magento\Sales\Model\Order'),
            $this->_objectManager->create('Compasso\Bpag\Helper\Library'),
            $this->_objectManager->create('Compasso\Bpag\Helper\Crypt'),
            $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface'),
            $this->getRequest()->getParam('days')
        );

        try {
            return $this->whenSuccess($abandoned->execute($this->getRequest()->getParam('data')));
        } catch (\Exception $exception) {
            return $this->whenError($exception->getMessage());
        }
    }

    /**
     * Abandoned access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Compasso_Bpag::Abandoned');
    }
}
