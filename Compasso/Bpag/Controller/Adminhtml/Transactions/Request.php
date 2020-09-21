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

namespace Compasso\Bpag\Controller\Adminhtml\Transactions;

use Compasso\Bpag\Controller\Ajaxable;
use Compasso\Bpag\Model\Transactions\Methods\Transactions;

/**
 * Class Request
 * @package Compasso\Bpag\Controller\Adminhtml
 */
class Request extends Ajaxable
{

    /**
     * Request constructor.
     *
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
     * @return Request
     */
    public function execute()
    {
        $transactions = new Transactions(
            $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface'),
            $this->_objectManager->create('Magento\Framework\App\ResourceConnection'),
            $this->_objectManager->create('Magento\Framework\Model\ResourceModel\Db\Context'),
            $this->_objectManager->create('Magento\Backend\Model\Session'),
            $this->_objectManager->create('Magento\Sales\Model\Order'),
            $this->_objectManager->create('Compasso\Bpag\Helper\Library'),
            $this->_objectManager->create('Compasso\Bpag\Helper\Crypt'),
            $this->getRequest()->getParam('id_magento'),
            $this->getRequest()->getParam('id_pagseguro'),
            $this->getRequest()->getParam('date_begin'),
            $this->getRequest()->getParam('date_end'),
            $this->getRequest()->getParam('status')
        );

        try {
            return $this->whenSuccess($transactions->request());
        } catch (\Exception $exception) {
            return $this->whenError($exception->getMessage());
        }
    }

    /**
     * Cancellation access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Compasso_Bpag::Transactions');
    }
}
