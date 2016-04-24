<?php

/*
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model;

use Glugox\Integration\Model\Integration\Result;

/**
 * Description of ImportResult
 *
 * @author Glugox <glugox@gmail.com>
 */
class ImportResult extends \Magento\Framework\DataObject {

    /**
     * Integration id
     *
     * @var int
     */
    public $integrationId = null;

    /**
     * Integration run id
     *
     * @var int
     */
    public $integrationRunId = null;

    /**
     * Result code
     *
     * @var int
     */
    public $resultCode = 0;

    /**
     * Started at time
     *
     * @var string
     */
    public $startedAt = null;

    /**
     * Finished at time
     *
     * @var string
     */
    public $finishedAt = null;

    /**
     * Array to store created products
     *
     * @var array
     */
    public $createdProducts = [];

    /**
     * Array to store updated products
     *
     * @var array
     */
    public $updatedProducts = [];

    /**
     * Array to store disabled products
     *
     * @var array
     */
    public $disabledProducts = [];

    /**
     * Array to store import errors
     *
     * @var array
     */
    public $errors = [];

    /**
     * Array to store import messages (with errors)
     *
     * @var array
     */
    public $messages = [];

    /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone */
    protected $_timezone;

    /** @var \Glugox\Integration\Model\Integration\ResultFactory */
    protected $_resultFactory;


    /**
     * Constructor
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Glugox\Integration\Model\Integration\ResultFactory $resultFactory,
        array $data = []
    )
    {
        $this->_timezone = $timezone;
        $this->_resultFactory = $resultFactory;
        parent::__construct($data);
    }


    /**
     * Insert import result, adds data to glugox_integration_result table.
     */
    protected function _insertImportResult() {

        $model = $this->_resultFactory->create()->load($this->integrationRunId, "integration_run_id");
        if(!$model->getId()){
            $model = $this->_resultFactory->create();
        }
        $model->addResultData($this)->save();
    }


    /**
     *
     * @param type $msg
     * @param type $isError
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addMessage($msg, $isError = false) {

        if ($isError) {
            $this->errors[] = $msg;
            $this->resultCode = Result::RESULT_CODE_ERROR;
        }
        $this->messages[] = $msg;

        return $this;
    }


    /**
     * @return \Glugox\Integration\Model\ImportResult
     * @param \Glugox\Integration\Model\Integration $integration
     */
    public function handleStart(\Glugox\Integration\Model\Integration $integration){

        $this->resultCode = Result::RESULT_CODE_RUNNING;
        $this->integrationRunId = $integration->getIntegrationCode() . '-' . strftime('%Y%m%d%H%M%S', $this->_timezone->scopeTimeStamp());
        $this->startedAt = strftime('%Y-%m-%d %H:%M:%S', $this->_timezone->scopeTimeStamp());

        $this->_insertImportResult();

        return $this;
    }

    /**
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function handleFinish(){

        $this->resultCode = !$this->isSuccess() ? Result::RESULT_CODE_ERROR : Result::RESULT_CODE_SUCCESS;
        $this->finishedAt = strftime('%Y-%m-%d %H:%M:%S', $this->_timezone->scopeTimeStamp());

        $this->_insertImportResult();

        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductCreated($sku) {
        $this->createdProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductUpdated($sku) {
        $this->updatedProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductDisabled($sku) {
        $this->disabledProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @return boolean
     */
    public function hasErrors() {
        return \count($this->errors) > 0;
    }


    /**
     *
     * @return boolean
     */
    public function isSuccess() {
        return !$this->hasErrors();
    }


    /**
     * Returns summary for the import
     *
     * @param boolean $detailed
     * @return string
     */
    public function getSummary($detailed = true) {

        $this->setData("numErrors", \count($this->errors));
        $this->setData("numCreatedProducts", \count($this->createdProducts));
        $this->setData("numUpdatedProducts", \count($this->updatedProducts));
        $this->setData("numDisabledProducts", \count($this->disabledProducts));
        $this->setData("errors", $this->errors);

        if ($detailed) {
            $this->setData("createdProducts", $this->createdProducts);
            $this->setData("updatedProducts", $this->updatedProducts);
            $this->setData("disabledProducts", $this->disabledProducts);
        } else {
        }
        return $this->toJson();
    }


}
