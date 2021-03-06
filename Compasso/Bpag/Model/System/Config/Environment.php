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

/**
 * Used in creating options for Sanbox|Production config value selection
 */
namespace Compasso\Bpag\Model\System\Config;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Environment
 * @package Compasso\Bpag\Model\System\Config
 */
class Environment implements ArrayInterface
{
    /**
     *
     */
    const SANDBOX = "sandbox";
    /**
     *
     */
    const PRODUCTION = "production";

    /**
     * @return array of options
     */
    public function toOptionArray()
    {
        return [
            self::SANDBOX => __('Sandbox'),
            self::PRODUCTION => __('Produção')
        ];
    }
}
