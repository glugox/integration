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


}
