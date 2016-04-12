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
 * @method int getLogCode()
 * @method \Glugox\Integration\Model\Integration\Log setLogCode(int $value)
 * @method string getStartedAt()
 * @method \Glugox\Integration\Model\Integration\Log setStartedAt(string $value)
 * @method string getFinishedAt()
 * @method \Glugox\Integration\Model\Integration\Log setFinishedAt(string $value)
 * @method string getLogText()
 * @method \Glugox\Integration\Model\Integration\Log setLogText(string $value)
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
        $this->setLogText($result->getSummary());
        $this->setIntegrationId($result->integrationId);
        $this->setLogCode(self::LOG_CODE_SUCCESS);
        $this->setStartedAt($result->startedAt);
        $this->setFinishedAt($result->finishedAt);

        return $this;
    }

}
