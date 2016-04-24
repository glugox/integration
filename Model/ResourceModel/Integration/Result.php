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
 * Integration Result resource model
 *
 * @author Glugox glugox@gmail.com
 */
class Result extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('glugox_integration_result', 'result_id');
    }

    /**
     * Returns last result run id by date
     *
     * @return string
     */
    public function getLastResultRunId(){

        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(["main_table" => $this->getMainTable()], ["integration_run_id"])
            ->order("main_table.started_at DESC")
        ;
        return $connection->fetchOne($select);

    }
}
