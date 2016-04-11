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
            ['name'],
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
         * Create table 'newsletter_problem'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('glugox_integration_problem'))
            ->addColumn(
                'problem_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Problem Id'
            )
            ->addColumn(
                'integration_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Integration Id'
            )
            ->addColumn(
                'problem_error_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'default' => '0'],
                'Problem Error Code'
            )
            ->addColumn(
                'problem_error_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                200,
                [],
                'Problem Error Text'
            )
            ->addIndex(
                $installer->getIdxName('glugox_integration_problem', ['integration_id']),
                ['integration_id']
            )
            ->addForeignKey(
                $installer->getFkName('glugox_integration_problem', 'integration_id', 'glugox_integration', 'integration_id'),
                'integration_id',
                $installer->getTable('glugox_integration'),
                'integration_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->setComment('Integration Problems');
        $installer->getConnection()->createTable($table);


        $installer->endSetup();
    }


}
