<?php
/**
 * Created by IntelliJ IDEA.
 * User: esc_loliveira
 * Date: 06/08/2020
 * Time: 13:21
 */

namespace Compasso\Bpag\Controller\Payment;

class Installments extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $_scopeConfig;
    protected $order;

    public function __construct(\Magento\Framework\App\Action\Context $context,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
                                \Magento\Sales\Model\Order $order) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfigInterface;
        $this->order = $order;
    }

    public function execute()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        $grandTotal = $cart->getQuote()->getGrandTotal();

        if($grandTotal > 0) {
            $numero_parcelas = ceil($this->_scopeConfig->getValue('payment/bpag/numero_parcelas'));
            $parcela_minima = round($this->_scopeConfig->getValue('payment/bpag/parcela_minima'),2);
            $parcelas = 1;

            if($numero_parcelas > 0){
                if($parcela_minima > 0 && $grandTotal > $parcela_minima) {
                    $parcelas_valor_minimo = floor($grandTotal/$parcela_minima);
                    $parcelas = min($parcelas_valor_minimo, $numero_parcelas);
                }
            }
            
            for($i=1;$i<$parcelas+1;$i++){
                $retorno[] = array('parcela'=>$i,'valor'=>number_format(round($grandTotal/$i,2),2,',','.'));
            }

            echo json_encode($retorno);
        }

//        echo ">>>".$this->_customerSession->getCustomer()->getId(); //Get Current customer ID
//        $customerData = $this->_customerSession->getCustomer(); //Get Current Customer Data
//        print_r($this->_customerSession);

    }
}