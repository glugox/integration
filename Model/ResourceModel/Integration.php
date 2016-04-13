<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model\ResourceModel;

/**
 * Integration resource model
 */
class Integration extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('glugox_integration', 'integration_id');
    }


    /**
     * Select active integration.
     *
     * @return array|boolean - Row data (array) or false if there is no corresponding row
     */
    public function selectActiveIntegration() {
        $connection = $this->getConnection();
        $select = $connection->select()
                ->from($this->getMainTable())
                ->where('status = ?', \Magento\Integration\Model\Integration::STATUS_ACTIVE);
        return $connection->fetchRow($select);
    }


    /**
     * Returns all integrations.
     *
     * @return array|boolean - Row data (array) or false if there is no corresponding row
     */
    public function getAllIntegrations() {
        $connection = $this->getConnection();
        $select = $connection->select()
                ->from($this->getMainTable())
        //->where('status = ?', \Magento\Integration\Model\Integration::STATUS_ACTIVE)
        ;
        return $connection->fetchAll($select);
    }

    /**
     * Resets all integrations.
     *
     * @return boolean
     */
    public function resetAllIntegrations() {
        return $this->getConnection()->update(
                $this->getMainTable(),
                ['status' => new \Zend_Db_Expr('0')],
                ['status=?' => \Magento\Integration\Model\Integration::STATUS_ACTIVE]
            );

    }

    /**
     * Cleans all integration helper tables.
     */
    public function cleanHelperTables() {

        foreach (\Glugox\Integration\Model\IntegrationService::TRUNCATEABLE_TABLES as $tableName){
            $this->getConnection()->truncateTable($tableName);
        }
    }

    /**
     * Returns number of records in the helper import ptoducts table.
     */
    public function getNumImportProductsLeft() {

        $select = $this->getConnection()->select()->from('glugox_import_products');
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $sql = $select->columns('COUNT(*)');
        $numProductsLeft = $this->getConnection()->fetchOne($sql);
        return intval($numProductsLeft);
    }







}
