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
namespace Compasso\Bpag\Model\Direct;

use Compasso\Bpag\Helper\Library;
/**
 * Class PaymentMethod
 * @package Compasso\Bpag\Model
 */
class CreditCardMethod
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     *
     * @var \Magento\Directory\Api\CountryInformationAcquirerInterface
     */
    protected $_countryInformation;

    /**
     * @var array
     */
    protected $_data;

    /**
     * PaymentMethod constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Framework\Module\ModuleList $moduleList,
        \Magento\Sales\Model\Order $order,
        \Compasso\Bpag\Helper\Library $library,
        $data = array()
    ) {
        $this->_data = $data;
        /** @var \Magento\Framework\App\Config\ScopeConfigInterface _scopeConfig */
        $this->_scopeConfig = $scopeConfigInterface;
        /** @var \Magento\Sales\Model\Order _order */
        $this->_order = $order;
        /** @var \Magento\Directory\Api\CountryInformationAcquirerInterface _countryInformation */
        $this->_countryInformation = $countryInformation;
        /** @var \Compasso\Bpag\Helper\Library _library */
        $this->_library = $library;

        ///** @var \Compasso\Bpag\Domains\Requests\DirectPayment\CreditCard _paymentRequest */
        //$this->_paymentRequest = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();
    }
    /**
     * @return \CompassoUolPagamentosPaymentRequest
     */
    public function createPaymentRequest()
    {
        $gibraltar_secret_key = $this->_scopeConfig->getValue('payment/bpag/secret_key');
        $gibraltar_access_id = $this->_scopeConfig->getValue('payment/bpag/access_id');
        $merchant = $this->_scopeConfig->getValue('payment/bpag/merchant');
        $account = $this->_scopeConfig->getValue('payment/bpag/account');

        $bpag_service_url = "/upbc-service-fe/v1/order/purchase";
        $grandTotal = number_format($this->_order->getGrandTotal(),2,'','');

        $address = $this->_order->getBillingAddress();

        $email = $this->_order->getCustomerEmail();
        $firstname = $address->getFirstname();
        //ToDo - verificar isso
        $middlename = '';
        $lastname = $address->getLastname();

        //$nome = $output = preg_replace('/\s+/', ' ', $firstname.' '.$middlename.' '.$lastname);

        $shipping = $this->_order->getShippingAddress();
        $streat = $shipping->getStreetLine(1)."\n".$shipping->getStreetLine(2);
        $city = $shipping->getCity();
        $region = $shipping->getRegion();
        $country_id = $shipping['country_id'];
        $postcode = $shipping->getPostcode();
        $telefone = $shipping['telephone'];

        //ToDo - Aqui deve ser implementado o envio dos dados para o Bpag V3
        print_r($this->_data);
        $cvv = $this->_data['card']['cvv'];
        $installments = $this->_data['installment']['parcela'];
        $cardNumber = $this->_data['card']['number'];
        $brand = $this->_data['card']['brand'];
        $exp_data = $this->_data['card']['exp_data'];
        $holder = $this->_data['holder']['name'];

        $document = preg_replace('/[^0-9]/','',$this->_data['sender_document']['number']);
        $b = explode("/", $this->_data['holder']['birth_date']);
        $birthdate = $b[2].'-'.$b[1].'-'.$b[0];

        $data = date("Y-m-d'T'H:i:s");
        $canonical = "POST\n\napplication/json\n$data\n$bpag_service_url";
        $hmac = hash_hmac ('sha256', $canonical, $gibraltar_secret_key, true);
        $base64 = base64_encode($hmac);
        $auth = "UOLWS $gibraltar_access_id:$base64:S2:0.2";

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization'=> $auth,
            'Merchant' => $merchant,
            'Account' => $account,
            'Date' => $data,
            'OnBehalfOfAccessId' => $gibraltar_access_id
        );
        $options = array('headers'=>$headers);

        $order = new \Compasso\Bpag\Model\Api\OrderRequest();
        $order->setAmount($grandTotal);
        $order->setChannel("magento");
        $order->setCurrency("BRL");
        $order->setProfile("profile");
        $order->setReference("MEGENTO_".$this->_order->getId());
        $order->setRequestDate("2019-12-05T19:20:30Z");

        //ToDo - resolver isso agora está mockado
        $paymentMethod = new \Compasso\Bpag\Model\Api\MeioDePagamento();
        $paymentMethod->setPaymentType("CARD");
        $paymentMethod->setPaymentSubtype("CREDIT");
        $paymentMethod->setProcessor("EREDE");
        $paymentMethod->setTechnology("WEBSERVICES");
        $paymentMethod->setFinancialInstitution("REDECARD");

        $creditCard = new \Compasso\Bpag\Model\Api\CreditCardRequest();
        $creditCard->setCvv($cvv);
        $creditCard->setInstallments($installments);
        //$creditCard->setSoftDescriptor("magento");
        $creditCard->setNumber($cardNumber);
        $creditCard->setBrand($brand);
        $creditCard->setExpDate($exp_data);
        $creditCard->setHolder($holder);

        $payment = new \Compasso\Bpag\Model\Api\PaymentRequest();
        $payment->setAmount($grandTotal);
        $payment->setPaymentMethod($paymentMethod);
        $payment->setCreditCard($creditCard);

        $details = new \Compasso\Bpag\Model\Api\OrderDetailsRequest();

        $customer = new \Compasso\Bpag\Model\Api\CustomerRequest();
        $customer->setType("CUSTOMER");
        $customer->setBirthday($birthdate);
        $customer->setDocument($document);
        $customer->setDocumentType("CPF");
        $customer->setEmail($email);
        $customer->setFirstName($firstname);
        $customer->setMiddleName($middlename);
        $customer->setLastName($lastname);

        $customer->setIpAddress($_SERVER['REMOTE_ADDR']);

        //ToDo - rever esses dados
        $customer->setId($this->_order->getId());
        $customer->setMaritalStatus("DIVORCED");
        $customer->setGender("MALE");

        if($streat != "") {
            $address = new \Compasso\Bpag\Model\Api\AddressRequest();
            $address->setCity($city);
            $address->setComplement("");
            $address->setCountry($country_id);
            $address->setDistrict("");
            $address->setStreet($streat);
            $address->setNumber("");
            $address->setState($region);
            $address->setType("CUSTOMER");
            $address->setZipCode($postcode);
            $customer->setAddresses([$address]);
        }

        if($telefone!=""){
            $tel = substr($telefone,-9);
            $ddd = str_replace($tel, '', $telefone);
            $phone = new \Compasso\Bpag\Model\Api\PhoneRequest();
            $phone->setAreaCode($ddd);
            $phone->setCountryCode("+55");
            $phone->setPhone($tel);
            $phone->setType("HOME");
            $customer->setPhones([$phone]);
        }

        $details->setCustomers([$customer]);
        $order->setPayments([$payment]);
        $order->setDetails($details);

        $api = new \Compasso\Bpag\Helper\RestClient($options);

        //echo "<br />Request: ".$order->__toString().'<br />';
        $url = "https://psp.uol.com.br$bpag_service_url";
        //ToDo - buscar essa url da configuração
        $resposta = $api->post($url, $order->__toString());
        //print_r($resposta);
        return $resposta;
    }

}
