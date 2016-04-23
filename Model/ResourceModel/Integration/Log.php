<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Model\ResourceModel\Integration;

/**
 * Integration Log resource model
 *
 * @author Glugox glugox@gmail.com
 */
class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('glugox_integration_log', 'log_id');
    }

    /**
     * Returns messages from the import log table.
     * If $fromTime argument is passed, it returns only messages at and after that time.
     *
     * @param string $fromTime Msyql datetime format string
     * @return array
     */
    public function getImportLogMessagesFrom($fromTime="0000-00-00 00:00:00"){
        $connection = $this->getConnection();
        $select = $connection->select()
                ->from($this->getMainTable())
                ->where('created_at >= ?', $fromTime)
        ;

        return $connection->fetchAll($select);
    }
}
