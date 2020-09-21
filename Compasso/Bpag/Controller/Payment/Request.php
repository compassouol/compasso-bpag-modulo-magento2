<?php
/**
 * 2007-2016 [COMPASSO UOL TECNOLOGIA.]
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

namespace Compasso\Bpag\Controller\Payment;

use Compasso\Bpag\Model\Direct\BoletoMethod;
use Compasso\Bpag\Model\Direct\CreditCardMethod;
use Compasso\Bpag\Model\Direct\DebitMethod;
use Compasso\Bpag\Model\PaymentMethod;

/**
 * Class Request
 *
 * @package Compasso\Bpag\Controller\Request
 */
class Request extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;
    
    private $order;
    
    private $orderId;

    //ToDo - verificar se vai ficar assim (tokenização, hoje é salvo em sessão)
    /** @var usei para pegar o numero do cartao, mas, o correto é pegar um token */
    protected $_coreSession;
    
    /** @var \Magento\Framework\Controller\Result\Json  */
    protected $result;
    
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultJsonFactory;

    protected $_transactionBuilder;

    /**
     * Request constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder
    ) {
        parent::__construct($context);

        $this->_coreSession = $coreSession;
        $this->_transactionBuilder = $transactionBuilder;
        $this->resultJsonFactory = $this->_objectManager->create('\Magento\Framework\Controller\Result\JsonFactory');
        $this->result = $this->resultJsonFactory->create();

        /** @var \Magento\Checkout\Model\Session _checkoutSession */
        $this->_checkoutSession = $this->_objectManager->create('\Magento\Checkout\Model\Session');

    }

    /**
     * Redirect to payment
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $lastRealOrder = $this->_checkoutSession->getLastRealOrder();

        /** @var \Magento\Sales\Model\Order $order */
        $this->order = $this->_objectManager->create('\Magento\Sales\Model\Order')->load(
            $lastRealOrder->getId()
        );

        if (is_null($lastRealOrder->getPayment())) {
            throw new \Magento\Framework\Exception\NotFoundException(__('No order associated.'));
        }

        $paymentData = $lastRealOrder->getPayment()->getData();

        $card_number = $this->_coreSession->getData("card_number");
        $card_cvv = $this->_coreSession->getData("card_cvv");
        $card_brand = $this->_coreSession->getData("card_brand");
        $card_exp_data = $this->_coreSession->getData("exp_data");

        $this->changeOrderHistory('compasso_bpag_aguardando_pagamento');

        if ($paymentData['method'] === 'compasso_uol_pagamentos_credit_card') {
            try {
                $this->orderId = $lastRealOrder->getId();

                if (is_null($this->orderId)) {
                    throw new \Exception("There is no order associated with this session.");
                }

                if (!isset($paymentData['additional_information']['credit_card_document'])
                    || ! isset($paymentData['additional_information']['credit_card_holder_name'])
                    || ! isset($paymentData['additional_information']['credit_card_holder_birthdate'])
                    || ! isset($paymentData['additional_information']['credit_card_installment'])
                    || ! isset($paymentData['additional_information']['credit_card_installment_value'])
                    ) {
                    throw new \Exception("Error passing data from checkout page to Compasso Pagamentos Request Controller");
                }

                $this->order = $this->loadOrder($this->orderId);
                /** @var \Compasso\Bpag\Model\Direct\CreditCardMethod $creditCard */
                $creditCard = new CreditCardMethod(
                    $this->_objectManager->create('Magento\Directory\Api\CountryInformationAcquirerInterface'),
                    $this->_objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface'),
                    $this->_objectManager->create('Magento\Framework\Module\ModuleList'),
                    $this->order,
                    $this->_objectManager->create('Compasso\Bpag\Helper\Library'),
                    $data = [
                        'sender_document' => $this->helperData()->formatDocument($paymentData['additional_information']['credit_card_document']),
                        'order_id' => $this->orderId,
                        'installment' => [
                            'parcela' => $paymentData['additional_information']['credit_card_installment'],
                            'valor'   => $paymentData['additional_information']['credit_card_installment_value']
                        ],
                        'holder' => [
                            'name'       => $paymentData['additional_information']['credit_card_holder_name'],
                            'birth_date' => $paymentData['additional_information']['credit_card_holder_birthdate'],

                        ],
                        'card' => [
                            'number' => $card_number,
                            'brand' => $card_brand,
                            'cvv' => $card_cvv,
                            'exp_data' => $card_exp_data
                        ]
                    ]
                );

                //$this->placeOrder($creditCard, $paymentData['method']);
                if($this->placeOrder($creditCard, $paymentData['method'])){
                    return $this->_redirect(sprintf('%s%s', $this->baseUrl(), 'bpag/payment/success'));
                } else {
                    return $this->_redirect(sprintf('%s%s', $this->baseUrl(), 'bpag/payment/failure'));
                }
                exit;
            } catch (\Exception $exception) {
                echo $exception->getMessage().'<br />';
                if (!is_null($this->order)) {
                    $this->changeOrderHistory('compasso_bpag_cancelada');
                }
                $this->clearSession();
                $this->whenError($exception->getMessage());
                echo $exception->getMessage();
                //return $this->_redirect(sprintf('%s%s', $this->baseUrl(), 'bpag/payment/failure'));
                exit;
            }
        }

        //ToDo - só existe o metodo cartão, se não vier cartão cancela o order
        /** change payment status in magento */
        //ToDo - mudar o último parametro, que significa notificar (mas estava dando erro ao enviar email)
        //echo "passou aqui cancelando order";
        $order->addStatusToHistory('compasso_bpag_cancelada', null, false);
        /** save order */
        $order->save();

        return $this->_redirect('bpag/payment/failure');
    }
    
    /**
     * Load a order by id
     *
     * @return \Magento\Sales\Model\Order
     */
    private function loadOrder($orderId)
    {
        return $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
    }
    
    /**
     * Load helper data
     *
     * @return \Compasso\Bpag\Helper\Data
     */
    private function helperData()
    {
        return $this->_objectManager->create('Compasso\Bpag\Helper\Data');
    }

    /**
     * Place order
     *
     * @param $payment
     * @return string
     */
    private function placeOrder($payment, $method)
    {
        $this->makeSession($method);
        return $this->whenSuccess($payment->createPaymentRequest(), $method);
    }
    
    /**
     * Change the magento order status
     *
     * @param $status
     */
    private function changeOrderHistory($status)
    {
        /** change payment status in magento */
        //ToDo - modificar isso, pois não notifica, pq dava erro ao enviar e-mail
        $this->order->addStatusToHistory($status, null, false);
        /** save order */
        if($status == 'compasso_bpag_cancelada') {
            $this->order->setStatus(\Sales\Model\Order::STATE_CANCELED);
        } else {
            $this->order->setStatus(\Sales\Model\Order::STATE_COMPLETE);
        }
        $this->order->save();
    }
    
    /**
     * Return when success
     *
     * @param $response
     * @return $this
     */
    private function whenSuccess($response, $method)
    {
        //echo '<pre>',print_r($response),'</pre>';
        $this->makeSession($method);
        if($response->info->http_code == 200) {
            $r = (Object) $response->decode_response();
            if($r->payments[0]->transactions[0]->status=="PAID") {
                $this->changeOrderHistory('compasso_bpag_paga');
            } else {
                $this->changeOrderHistory('compasso_bpag_pagamento_rejeitado');
            }

            //Save aditional info payment return
            $this->createTransaction($r->payments[0]->transactions[0]);
            return true;
        } else {
            $this->changeOrderHistory('compasso_bpag_cancelada');
            return false;
        }
    }
    
    /**
     * Create new compasso uol pagamentos payment session data
     *
     * @param $response
     */
    private function makeSession($method)
    {
        $this->session()->setData([
            'bpag_payment' => [
                'payment_type'  => $method,
                'order_id'      => $this->orderId,
                'nome'          => $this->order->getBillingAddress()->getFirstname()
            ]
        ]);
    }

    public function createTransaction($paymentData = array())
    {

        //echo '<pre>',print_r($paymentData),'</pre>';
        $arrPaymentData = array(
            'date' => $paymentData->date,
            'action' => $paymentData->action,
            'status' => $paymentData->status,
            'normalizedFIStatus' => $paymentData->normalizedFIStatus,
            'normalizedFIStatusCode' => $paymentData->normalizedFIStatusCode,
            'amount' => $paymentData->amount,
            'installments' => $paymentData->creditCard->installments,
            'authorizationCode' => $paymentData->creditCard->authorizationCode,
            'nsu' => $paymentData->creditCard->nsu,
            'rawCode' => $paymentData->creditCard->rawCode,
            'rawMessage' => $paymentData->creditCard->rawMessage,
            'integratorCode' => $paymentData->creditCard->integratorCode
        );

        //echo '<pre>',print_r($arrPaymentData),'</pre>';
        try {
            //get payment object from order object
            $payment = $this->order->getPayment();
            $payment->setLastTransId($arrPaymentData['integratorCode']);
            $payment->setTransactionId($arrPaymentData['integratorCode']);
            $payment->setAdditionalInformation(
                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $arrPaymentData]
            );

            $message = "date: ".$paymentData->date."<br />action: ".$paymentData->action."<br />status: ".$paymentData->status."<br />normalizedFIStatus: ".$paymentData->normalizedFIStatus."<br />normalizedFIStatusCode: ".$paymentData->normalizedFIStatusCode."<br />
                    amount: ".$paymentData->amount."<br />installments: ".$paymentData->creditCard->installments."<br />authorizationCode: ".$paymentData->creditCard->authorizationCode."<br />nsu: ".$paymentData->creditCard->nsu."<br />rawCode: ".$paymentData->creditCard->rawCode."<br />
                    rawMessage: ".$paymentData->creditCard->rawMessage."<br />integratorCode: ".$paymentData->creditCard->integratorCode."<br />";

            //get the object of builder class
            $trans = $this->_transactionBuilder;
            $transaction = $trans->setPayment($payment)
                ->setOrder($this->order)
                ->setTransactionId($arrPaymentData['integratorCode'])
                ->setAdditionalInformation(
                    [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $arrPaymentData]
                )
                ->setFailSafe(true)
                //build method creates the transaction and returns the object
                ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            $payment->setParentTransactionId(null);
            $payment->save();
            $this->order->save();

            return  $transaction->save()->getTransactionId();
        } catch (Exception $e) {
            //log errors here
        }

    }

    /**
     * Clear session storage
     */
    private function clearSession()
    {
        //$this->_objectManager->create('Magento\Framework\Session\SessionManager')->clearStorage();
    }
    
    /**
     * Return when fails
     *
     * @param $message
     * @return $this
     */
    private function whenError($message)
    {
        return $this->result->setData([
            'success' => false,
            'payload' => [
                'error'    => $message,
                'redirect' => sprintf('%s%s', $this->baseUrl(), 'bpag/payment/failure')
            ]
        ]);
    }
    
    /**
     * Get base url
     *
     * @return string url
     */
    private function baseUrl()
    {
        return $this->_objectManager->create('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl();
    }

    /**
     * Create a new session object
     *
     * @return \Magento\Framework\Session\SessionManager
     */
    private function session()
    {
        return $this->_coreSession;
    }
}
