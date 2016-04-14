<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup,
            ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();


        /**
         * Create table 'glugox_integration'
         *
         *  +------------------+----------------------+------+-----+---------------------+-----------------------------+
            | Field            | Type                 | Null | Key | Default             | Extra                       |
            +------------------+----------------------+------+-----+---------------------+-----------------------------+
            | integration_id   | int(10) unsigned     | NO   | PRI | NULL                | auto_increment              |
            | integration_code | varchar(255)         | NO   | UNI | NULL                |                             |
            | name             | varchar(255)         | NO   | UNI | NULL                |                             |
            | ca_file          | varchar(255)         | YES  |     | NULL                |                             |
            | client_file      | varchar(255)         | YES  |     | NULL                |                             |
            | key_file         | varchar(255)         | YES  |     | NULL                |                             |
            | cert_pass        | varchar(255)         | YES  |     | NULL                |                             |
            | importer_class   | varchar(255)         | YES  |     | NULL                |                             |
            | enabled          | smallint(5) unsigned | NO   |     | NULL                |                             |
            | status           | smallint(5) unsigned | NO   |     | NULL                |                             |
            | created_at       | timestamp            | NO   |     | CURRENT_TIMESTAMP   |                             |
            | updated_at       | timestamp            | NO   |     | 0000-00-00 00:00:00 | on update CURRENT_TIMESTAMP |
            | service_url      | varchar(255)         | YES  |     | NULL                |                             |
            +------------------+----------------------+------+-----+---------------------+-----------------------------+
         *
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('glugox_integration')
        )->addColumn(
            'integration_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Integration ID'
        )->addColumn(
            'integration_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Human readable lowercase a-z code'
        )->addColumn(
            'sku_prefix',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Prefix to appent to the sku from the service'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Integration name'
        )->addColumn(
            'ca_file',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Path to ca.pem file if needed'
        )->addColumn(
            'client_file',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Path to client.pem file if needed'
        )->addColumn(
            'key_file',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Path to key.pem file if needed'
        )->addColumn(
            'cert_pass',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Cert password if exists'
        )->addColumn(
            'importer_class',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Class that overrides basic importer class for customization'
        )->addColumn(
            'enabled',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            1,
            ['unsigned' => true, 'nullable' => false],
            'Wether integration is enabled or not'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            1,
            ['unsigned' => true, 'nullable' => false],
            'Integration status'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_UPDATE],
            'Update Time'
        )->addColumn(
            'service_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Main endpoint url of the service'
        )->addIndex(
            $installer->getIdxName(
                $installer->getTable('glugox_integration'),
                ['name'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['name'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addIndex(
            $installer->getIdxName(
                $installer->getTable('glugox_integration'),
                ['integration_code'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['integration_code'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        );

        $installer->getConnection()->createTable($table);

        

        /**
         * Create table glugox_import_products
         *
         *  +-------------------+------------------+------+-----+---------+----------------+
            | Field             | Type             | Null | Key | Default | Extra          |
            +-------------------+------------------+------+-----+---------+----------------+
            | id                | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
            | importer_code     | varchar(255)     | NO   | MUL | NULL    |                |
            | sku               | varchar(255)     | NO   |     | NULL    |                |
            | category          | varchar(255)     | NO   |     | NULL    |                |
            | name              | varchar(255)     | NO   | UNI | NULL    |                |
            | warranty          | varchar(255)     | YES  |     | NULL    |                |
            | brend             | varchar(255)     | YES  |     | NULL    |                |
            | qttyinstock       | int(11)          | YES  |     | NULL    |                |
            | tax               | decimal(12,4)    | NO   |     | 0.0000  |                |
            | price             | decimal(12,4)    | NO   |     | 0.0000  |                |
            | cost              | decimal(12,4)    | YES  |     | 0.0000  |                |
            | description       | text             | YES  |     | NULL    |                |
            | short_description | text             | YES  |     | NULL    |                |
            | image_url         | varchar(255)     | YES  |     | NULL    |                |
            | special_offer     | decimal(12,4)    | NO   |     | 0.0000  |                |
            | time_changed      | datetime         | YES  |     | NULL    |                |
            | invalidated       | int(11)          | YES  |     | NULL    |                |
            +-------------------+------------------+------+-----+---------+----------------+
         *
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('glugox_import_products')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Import ID'
        )->addColumn(
            'importer_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Code of the importer from db tbale: glugox_integration'
        )->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Product SKU'
        )->addColumn(
            'category',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Product category'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Product name'
        )->addColumn(
            'warranty',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Product warranty period'
        )->addColumn(
            'brend',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Product brend'
        )->addColumn(
            'qttyinstock',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['nullable' => true],
            'Product stock quantity'
        )->addColumn(
            'tax',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000'],
            'Product tax value'
        )->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000'],
            'Product price'
        )->addColumn(
            'cost',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => true, 'default' => '0.0000'],
            'Product cost'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Product description'
        )->addColumn(
            'short_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Product short description'
        )->addColumn(
            'image_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Product image url'
        )->addColumn(
            'special_offer',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => false, 'default' => '0.0000'],
            'Product special offer'
        )->addColumn(
            'time_changed',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'Product last update time'
        )->addColumn(
            'invalidated',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            1,
            ['nullable' => true],
            'Flag wether this row was processed'
        )->addIndex(
            $installer->getIdxName(
                $installer->getTable('glugox_import_products'),
                ['sku'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['sku'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
                $installer->getFkName(
                    'glugox_import_products',
                    'importer_code',
                    'glugox_integration',
                    'integration_code'
                ),
                'importer_code',
                $installer->getTable('glugox_integration'),
                'integration_code',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($table);



        /**
         * Create table 'glugox_integration_log'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('glugox_integration_log'))
            ->addColumn(
                'log_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Log Id'
            )
            ->addColumn(
                'integration_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Integration Id'
            )
            ->addColumn(
                'log_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'default' => '0'],
                'Log Code'
            )
            ->addColumn(
                'started_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Start Time'
            )
            ->addColumn(
                'finished_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Finish Time'
            )
            ->addColumn(
                'log_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Log Text'
            )
            ->addIndex(
                $installer->getIdxName('glugox_integration_log', ['integration_id']),
                ['integration_id']
            )
            ->addForeignKey(
                $installer->getFkName('glugox_integration_log', 'integration_id', 'glugox_integration', 'integration_id'),
                'integration_id',
                $installer->getTable('glugox_integration'),
                'integration_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Integration Log');
        $installer->getConnection()->createTable($table);


        $installer->endSetup();
    }


}
