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

namespace Compasso\Bpag\Helper;

class Library
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
	/**
     * @var \Magento\Framework\Module\ModuleList
     */
	protected $_moduleList;
    /**
     * Library constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
		\Magento\Framework\Module\ModuleList $moduleList
    ) {
		$this->_moduleList = $moduleList;
        $this->loader();
        $this->_scopeConfig = $scopeConfigInterface;
    }
    /**
     * Get the access credential
     * @return CompassoUolPagamentosAccountCredentials
     */
    public function getCompassoPagamentosCredentials()
    {
        $email = $this->_scopeConfig->getValue('payment/bpag/email');
        $token = $this->_scopeConfig->getValue('payment/bpag/token');
        echo $email.' - '.$token;
        return null;
    }

    /**
     * Load library vendor
     */
    private function loader()
    {
        /** @var \Magento\Framework\App\ObjectManager $objectManager */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
        $timezone = $objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        /** @var \Magento\Framework\App\ProductMetadataInterface $productMetadata */

        //set the store timezone to the script
        date_default_timezone_set($timezone->getConfigTimezone());
//        \CompassoBpag\Library::initialize();
//		\CompassoBpag\Library::cmsVersion()->setName("Magento")->setRelease($productMetadata->getVersion());
//        \CompassoBpag\Library::moduleVersion()->setName($this->_moduleList->getOne('Compasso_Bpag')['name'])
//			->setRelease($this->_moduleList->getOne('Compasso_Bpag')['setup_version']);
    }

    /**
     * Set the environment configured in the Compasso UOL Pagamentos module
     */
    public function setEnvironment()
    {
//        \PagSeguro\Configuration\Configure::setEnvironment(
//            $this->_scopeConfig->getValue('payment/bpag/environment')
//        );
    }
    /**
     * Set the environment configured in the Compasso UOL Pagamentos module
     */
    public function getEnvironment()
    {
       return $this->_scopeConfig->getValue('payment/bpag/environment');
    }

    /**
     * Set the charset configured in the Compasso UOL Pagamentos module
     */
    public function setCharset()
    {
//        \PagSeguro\Configuration\Configure::setCharset(
//            $this->_scopeConfig->getValue('payment/bpag/charset')
//        );
    }

    /**
     * Set the log and log location configured in the Compasso UOL Pagamentos module
     */
    public function setLog()
    {
//        \PagSeguro\Configuration\Configure::setLog(
//            $this->_scopeConfig->getValue('payment/bpag/log'),
//            $this->_scopeConfig->getValue('payment/bpag/log_file')
//        );
    }


    /**
     * Get session
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSession()
    {
        try {
//            $session = \PagSeguro\Services\Session::create(
//                $this->getCompassoPagamentosCredentials()
//            );
//            return $session->getResult();
            return null;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Get image full frontend url
     * @return type
     */
    public function getImageUrl($imageModulePath)
    {
        /** @var \Magento\Framework\App\ObjectManager $om */
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	/** @var \Magento\Framework\View\Asset\Repository */
	$viewRepository = $objectManager->get('\Magento\Framework\View\Asset\Repository');
	return $viewRepository->getUrl($imageModulePath);
    }
}
