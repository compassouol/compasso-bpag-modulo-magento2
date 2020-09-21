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
 
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class UpgradeSchema implements UpgradeSchemaInterface
{

    const COMPASSO_UOL_PAGAMENTOS_ORDERS = 'bpag_orders';
    
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        //No previous version found, installation, InstallSchema was just executed
        if(!$context->getVersion()) {
        }
        
        //code to upgrade to 2.0.1
        if (version_compare($context->getVersion(), '2.0.1') < 0) {
            $this->createPagSeguroOrdersTable($setup);
            $this->integratePagSeguroAndOrdersGrid($setup);
            $this->cleanUiBookmark($setup);
        }

        if (version_compare($context->getVersion(), '2.0.2') < 0) {
            $this->addPartiallyRefundedColumnCompassoPagamentosOrders($setup);
        }
        
        $setup->endSetup();
        
    }

    private function createPagSeguroOrdersTable($setup) 
    {
        $tableName = $setup->getTable(self::COMPASSO_UOL_PAGAMENTOS_ORDERS);
        // Check if the table already exists
        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    11,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Entity ID'
                )
                ->addColumn(
                    'order_id',
                    TABLE::TYPE_INTEGER,
                    11,
                    [],
                    'Order id'
                )
                ->addColumn(
                    'transaction_code',
                    Table::TYPE_TEXT,
                    80,
                    [],
                    'Transaction code'
                )
                ->addColumn(
                    'sent',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false, 'default' => 0],
                    'Sent Emails'
                )
                ->addColumn(
                    'environment',
                    Table::TYPE_TEXT,
                    40,
                    [],
                    'Environment'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Compasso UOL Pagamentos Orders Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $setup->getConnection()->createTable($table);
        }
    }

    private function integratePagSeguroAndOrdersGrid($setup)
    {
        //add transaction code column
        $setup->getConnection()
            ->addColumn(
                $setup->getTable('sales_order_grid'),
                'transaction_code',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 80,
                    'comment' => 'Compasso UOL Pagamentos Transaction Code'
                ]
            );
        
        //add environment column
        $setup->getConnection()
            ->addColumn(
                $setup->getTable('sales_order_grid'),
                'environment',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 40,
                    'comment' => 'Compasso UOL Pagamentos Environment'
                ]
            );
    }

    private function cleanUiBookmark($setup)
    {
        $setup->getConnection()
            ->delete($setup->getTable('ui_bookmark'), "namespace='sales_order_grid'");
    }

    private function addPartiallyRefundedColumnCompassoPagamentosOrders($setup)
    {
        $tableName = $setup->getTable(self::COMPASSO_UOL_PAGAMENTOS_ORDERS);

        // Check if the table already exists
        if ($setup->getConnection()->isTableExists($tableName) == true && $setup->getConnection()->tableColumnExists($tableName, 'partially_refunded') === false) {
            $setup->getConnection()
                ->addColumn(
                    $tableName,
                    'partially_refunded',
                    array(
                        'type' => Table::TYPE_BOOLEAN,
                        'nullable' => false,
                        'default' => 0,
                        'comment' => 'Show if order is already partially refunded',
                    )
                );
        }
    }
}
