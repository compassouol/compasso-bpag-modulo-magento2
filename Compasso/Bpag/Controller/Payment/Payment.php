<?php
/**
 * Created by IntelliJ IDEA.
 * User: esc_loliveira
 * Date: 06/08/2020
 * Time: 13:21
 */

namespace Compasso\Bpag\Controller\Payment;

class Payment extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $_session;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $session) {
        parent::__construct($context);
        $this->_session = $session;
    }

    public function execute()
    {
//        echo ">>>".$this->_customerSession->getCustomer()->getId(); //Get Current customer ID
//        $customerData = $this->_customerSession->getCustomer(); //Get Current Customer Data
//        print_r($this->_customerSession);

    }
}