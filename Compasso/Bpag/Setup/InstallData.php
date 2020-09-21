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

namespace Compasso\Bpag\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Prepare database for install
         */
        $setup->startSetup();

        /**
         * Compasso UOL Pagamentos Order Status
         */
        $statuses = [
            'compasso_bpag_iniciado'  => __('Bpag Iniciado'),
            'compasso_bpag_aguardando_pagamento' => __('Aguardando Pagamento (Bpag)'),
            'compasso_bpag_cancelada' => __('Cancelada (Bpag)'),
            'compasso_bpag_chargeback_debitado'  => __('Compasso Bpag Chargeback Debitado'),
            'compasso_bpag_devolvida'  => __('Compasso Bpag Devolvida'),
            'compasso_bpag_disponivel'  => __('Compasso Bpag Disponível'),
            'compasso_bpag_em_analise'  => __('Pagamento Em Análise (Bpag)'),
            'compasso_bpag_erro'  => __('Erro no pagamento (Bpag)'),
            'compasso_bpag_em_disputa'  => __('Compasso Bpag Em Disputa'),
            'compasso_bpag_paga'  => __('Paga (Bpag)'),
            'compasso_bpag_pagamento_rejeitado' => __('Rejeitado (Bpag)')
        ];

        foreach ($statuses as $code => $info) {
            $status[] = [
                'status' => $code,
                'label' => $info
            ];
            $state[] = [
                'status' => $code,
                'state' => 'new',
                'is_default' => 0,
                'visible_on_front' => '1'
            ];
        }
        $setup->getConnection()
            ->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $status);

        /**
         * Compasso UOL Pagamentos Order State
         */
        $state[0]['is_default'] = 1;
        $setup->getConnection()
            ->insertArray(
                $setup->getTable('sales_order_status_state'),
                ['status', 'state', 'is_default', 'visible_on_front'],
                $state
            );
        unset($data);

        /**
         * Compasso UOL Pagamentos Store Reference
         */
        $data[] = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'bpag/store/reference',
            'value' => \Compasso\Bpag\Helper\Data::generateStoreReference()
        ];
        $setup->getConnection()
            ->insertArray($setup->getTable('core_config_data'), ['scope', 'scope_id', 'path', 'value'], $data);

        /**
         * Prepare database after install
         */
        $setup->endSetup();
    }
}
