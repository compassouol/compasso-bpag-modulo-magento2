<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Compasso\Bpag\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $statuses = [
            'compasso_bpag_iniciado',
            'compasso_bpag_aguardando_pagamento',
            'compasso_bpag_cancelada',
            'compasso_bpag_chargeback_debitado',
            'compasso_bpag_devolvida',
            'compasso_bpag_disponivel',
            'compasso_bpag_em_analise',
            'compasso_bpag_em_contestacao',
            'compasso_bpag_em_disputa',
            'compasso_bpag_paga'
        ];
        
        $paths = [
            'bpag/store/reference',
            'payment/bpag/active',
            'payment/bpag/title',
            'payment/bpag/email',
            'payment/bpag/token',
            'payment/bpag/redirect',
            'payment/bpag/notification',
            'payment/bpag/charset',
            'payment/bpag/log',
            'payment/bpag/log_file',
            'payment/bpag/checkout',
            'payment/bpag/environment',
            'payment/bpag/abandoned_active'
        ];

        $setup->startSetup();
        $this->dropCompassoPagamentosOrdersTable($setup);
        $this->dropColumnsFromSalesOrderGrid($setup);
        $this->removeDataFromSalesOrderStatus($setup, $statuses);
        $this->removeDataFromSalesOrderStatusState($setup, $statuses);
        $this->removeDataFromCoreConfigData($setup, $paths);
        $setup->endSetup();
    }

    private function dropCompassoPagamentosOrdersTable($setup)
    {
        $setup->getConnection()->dropTable($setup->getTable('compasso_uol_pagamentos_orders'));
    }
    
    private function dropColumnsFromSalesOrderGrid($setup)
    {
        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order_grid'),
            'transaction_code'
        );
        
        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order_grid'),
            'environment'
        );
    }
    //sales_order_status
    private function removeDataFromSalesOrderStatus($setup, $statuses)
    {
        foreach ($statuses as $status) {
            $setup->getConnection()
                ->delete($setup->getTable('sales_order_status'), "status='$status'");
        }
    }
    //sales_order_status_state
    private function removeDataFromSalesOrderStatusState($setup, $statuses)
    {
        foreach ($statuses as $status) {
            $setup->getConnection()
                ->delete($setup->getTable('sales_order_status_state'), "status='$status'");
        }
    }
    //core_config_data
    private function removeDataFromCoreConfigData($setup, $paths)
    {
        foreach ($paths as $path) {
            $setup->getConnection()
                ->delete($setup->getTable('core_config_data'), "path='$path'");
        }
    }
    
    private function removeModuleSetup($setup)
    {
        $setup->getConnection()
            ->delete($setup->getTable('setup_module'), "module='Compasso_Bpag'");
    }
}