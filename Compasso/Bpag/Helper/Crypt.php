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

/**
 * Class Crypt
 * @package Compasso\Bpag\Helper
 */
class Crypt
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Library constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->_scopeConfig = $scopeConfigInterface;
    }

    /**
     * Encrypt data
     *
     * @param $password
     * @param $data
     * @return string
     */
    public function encrypt($password, $data)
    {
        $salt = substr(md5(mt_rand(), true), 8);
        $key = md5($password . $salt, true);
        $iv  = md5($key . $password . $salt, true);
        $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
        return base64_encode('Salted__' . $salt . $ct);
    }

    /**
     * Decrypt data
     *
     * @param $password
     * @param $data
     * @return string
     */
    public function decrypt($password, $data)
    {
        $data = base64_decode($data);
        $salt = substr($data, 8, 8);
        $ct   = substr($data, 16);
        $key = md5($password . $salt, true);
        $iv  = md5($key . $password . $salt, true);
        $pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);
        return $pt;
    }
}
