<?php

/*
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Model\Integration\Import;

/**
 * Description of Importer
 *
 * @author Eko
 */
abstract class Importer implements \Glugox\Integration\Model\Integration\Import\ImporterInterface {

    /*
     * Integration Status values
     */
    const IMPORTER_STATUS_START       = 101;
    const IMPORTER_STATUS_VALIDATION  = 111;
    const IMPORTER_STATUS_ERROR       = 501;
    const IMPORTER_STATUS_SUCCESS     = 901;

    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /** @var \Glugox\Integration\Model\Integration */
    protected $_integration;

    /** @var int Integration status */
    protected $_status;

    /** @var array errors */
    protected $_errors;

    /** @var string Prefix */
    protected $_prefix = '';

    /** @var \Glugox\Integration\Model\ImportResult Import Result */
    protected $_result;

    /** @var \Glugox\Integration\Model\Integration\LogFactory */
    protected $_logFactory;

    /** @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone */
    protected $_timezone;

    /**
     *
     * @param type $status
     * @return \Glugox\Integration\Model\Integration\Import\Importer
     */
    protected function setStatus($status){
        $this->_status = $status;
        switch ($status){
            case self::IMPORTER_STATUS_START:
                // mark this integration as active/started
                $this->_integration->setStatus(\Glugox\Integration\Model\Integration::STATUS_ACTIVE)->save();
                break;
            case self::IMPORTER_STATUS_ERROR:
            case self::IMPORTER_STATUS_SUCCESS:
                // mark this integration as inactive/finished
                $this->_integration->setStatus(\Glugox\Integration\Model\Integration::STATUS_INACTIVE)->save();
                break;

        }
        return $this;
    }

    /**
     * @return int status value
     */
    public function getStatus(){
        return $this->_status;
    }

    /**
     *
     * @param \Glugox\Integration\Model\Integration $integration
     * @return \Glugox\Integration\Model\Integration\Import\Importer
     */
    public function setIntegration(\Glugox\Integration\Model\Integration $integration){
        $this->_integration = $integration;
        $this->_result->integrationId = $this->_integration->getId();
        return $this;
    }


    /**
     * @param \Glugox\Integration\Helper\Data $helper
     */
    public function __construct(
            \Glugox\Integration\Helper\Data $helper,
            \Glugox\Integration\Model\Integration\LogFactory $logFactory,
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
            ) {
        $this->_helper = $helper;
        $this->_result = new \Glugox\Integration\Model\ImportResult;
        $this->_logFactory = $logFactory;
        $this->_timezone = $timezone;
    }

    /**
     * Runs the data import
     *
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function import() {
        $this->_start();
        $validation = $this->_validate();
        if(true === $validation){
            $this->_import();
        }
        $this->_end();

        return $this->_result;
    }

    /**
     * Starts the import
     */
    protected function _start(){

        $this->_errors = array();
        $this->_result->startedAt = strftime('%Y-%m-%d %H:%M:%S', $this->_timezone->scopeTimeStamp());
        $this->setStatus(self::IMPORTER_STATUS_START);

        $this->_info("Importer start (".$this->_integration->getIntegrationCode().")...");
        //$this->_info($this->_integration->getData());
    }

    /**
     * Ends the import
     */
    protected function _end(){
        $this->setStatus(self::IMPORTER_STATUS_SUCCESS);

        // mark this integration as inactive/finished
        $this->_integration->setStatus(\Glugox\Integration\Model\Integration::STATUS_INACTIVE)->save();
        $this->_result->finishedAt = strftime('%Y-%m-%d %H:%M:%S', $this->_timezone->scopeTimeStamp());

        $this->_info("Importer end (".$this->_integration->getIntegrationCode().")...");

        // log the import results
        $this->_logImportResult($this->_result);
    }

    /**
     * Runs the data import
     *
     * @return boolaen
     */
    protected function _import() {
        $serviceUrl = $this->_integration->getServiceUrl();
        $this->_info("Data loading: ".$serviceUrl."...");
    }

    /**
     *
     * @return boolean
     */
    protected function _validate(){
        return !$this->_result->hasErrors();
    }

    /**
     * Logs import result, adds data to glugox_integration_log table.
     *
     * @param \Glugox\Integration\Model\ImportResult $result
     */
    protected function _logImportResult() {
        $this->_logFactory->create()->addResultData($this->_result)->save();
    }


    /**
     *
     * @param type $msg
     * @param type $isError
     */
    protected function _info($msg, $isError=false){
        if (!is_string($msg)) {
            $msg = '<pre>' . print_r($msg, true) . '</pre>';
        }
        if($isError){
            $msg = "ERROR: " . $msg;
        }
        $this->_helper->info($msg);
        $this->_result->addMessage($msg, $isError);
    }


}
