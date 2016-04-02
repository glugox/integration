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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
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

        $installer->endSetup();

    }
}
