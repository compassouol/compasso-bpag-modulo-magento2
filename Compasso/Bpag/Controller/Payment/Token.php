<?php
/**
 * Created by IntelliJ IDEA.
 * User: esc_loliveira
 * Date: 13/08/2020
 * Time: 09:10
 */

namespace Compasso\Bpag\Controller\Payment;

/**
 * Class Request
 *
 * @package Compasso\Bpag\Controller\Payment
 */
class Token extends \Magento\Framework\App\Action\Action {

    protected $_coreSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        parent::__construct($context);
        $this->_coreSession = $coreSession;
    }

    public function execute()
    {
        $post = $this->getRequest()->getPost();
        $this->_coreSession->setData("card_number", $post['cardNumber']);
        $this->_coreSession->setData("card_cvv", $post['cvv']);
        $this->_coreSession->setData("card_brand", $post['brand']);
        $this->_coreSession->setData("exp_data", $post['expYear'].'-'.str_pad($post['expMonth'],2,'0',STR_PAD_LEFT));
    }
}