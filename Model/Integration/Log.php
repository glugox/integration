<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model\Integration;

/**
 * Integration log model
 *
 * @method \Glugox\Integration\Model\ResourceModel\Integration\Log _getResource()
 * @method \Glugox\Integration\Model\ResourceModel\Integration\Log getResource()
 * @method int getIntegrationId()
 * @method \Glugox\Integration\Model\Integration\Log setIntegrationId(int $value)
 * @method \string getIntegrationRunId()
 * @method \Glugox\Integration\Model\Integration setIntegrationRunId(\string $name)
 * @method int getLogCode()
 * @method \Glugox\Integration\Model\Integration\Log setLogCode(int $value)
 * @method string getLogAlias()
 * @method \Glugox\Integration\Model\Integration\Log setLogAlias(string $value)
 * @method string getStartedAt()
 * @method \Glugox\Integration\Model\Integration\Log setStartedAt(string $value)
 * @method string getFinishedAt()
 * @method \Glugox\Integration\Model\Integration\Log setFinishedAt(string $value)
 * @method string getLogText()
 * @method \Glugox\Integration\Model\Integration\Log setLogText(string $value)
 * @method string getCreatedAt()
 * @method \Glugox\Integration\Model\Integration\Log setCreatedAt(string $value)
 *
 *
 * @author Glugox
 */
class Log extends \Magento\Framework\Model\AbstractModel
{

    const LOG_CODE_SUCCESS = 1;
    const LOG_CODE_ERROR = 0;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize Model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Glugox\Integration\Model\ResourceModel\Integration\Log');
    }

    /**
     * Add Result Data
     *
     * @param \Glugox\Integration\Model\ImportResult $result
     * @return $this
     */
    public function addResultData(\Glugox\Integration\Model\ImportResult $result)
    {
        $this->setIntegrationId($result->integrationId);
        $this->setIntegrationRunId($result->integrationRunId);
        $this->setCreatedAt();

        return $this;
    }

    /**
     * Returns messages from the import log table.
     * If $fromTime argument is passed, it returns only messages at and after that time.
     *
     * @param string $fromTime Msyql datetime format string
     * @param string $integrationRunId Select only from specific integration run, if null -> any
     * @return array
     */
    public function getImportLogMessagesFrom($fromTime, $integrationRunId = null){
        return $this->getResource()->getImportLogMessagesFrom($fromTime, $integrationRunId);
    }

}
