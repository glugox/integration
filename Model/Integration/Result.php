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
 * Integration result model
 *
 * @method \Glugox\Integration\Model\ResourceModel\Integration\Result _getResource()
 * @method \Glugox\Integration\Model\ResourceModel\Integration\Result getResource()
 * @method int getIntegrationId()
 * @method \Glugox\Integration\Model\Integration\Result setIntegrationId(int $value)
 * @method \string getIntegrationRunId()
 * @method \Glugox\Integration\Model\Integration setIntegrationRunId(\string $name)
 * @method int getResultCode()
 * @method \Glugox\Integration\Model\Integration\Result setResultCode(int $value)
 * @method string getStartedAt()
 * @method \Glugox\Integration\Model\Integration\Result setStartedAt(string $value)
 * @method string getFinishedAt()
 * @method \Glugox\Integration\Model\Integration\Result setFinishedAt(string $value)
 * @method string getResultText()
 * @method \Glugox\Integration\Model\Integration\Result setResultText(string $value)
 *
 * @author Glugox
 */
class Result extends \Magento\Framework\Model\AbstractModel
{

    const RESULT_CODE_RUNNING   = 1;
    const RESULT_CODE_SUCCESS   = 2;
    const RESULT_CODE_ERROR     = 4;

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
        $this->_init('Glugox\Integration\Model\ResourceModel\Integration\Result');
    }

    /**
     * Add Result Data
     *
     * @param \Glugox\Integration\Model\ImportResult $result
     * @return $this
     */
    public function addResultData(\Glugox\Integration\Model\ImportResult $result)
    {
        $this->setResultText($result->getSummary());
        $this->setIntegrationId($result->integrationId);
        $this->setIntegrationRunId($result->integrationRunId);
        $this->setResultCode($result->resultCode);
        $this->setStartedAt($result->startedAt);
        $this->setFinishedAt($result->finishedAt);

        return $this;
    }


    /**
     * Returns last result run id by date
     *
     * @return string
     */
    public function getLastResultRunId(){
        return $this->getResource()->getLastResultRunId();
    }

}
