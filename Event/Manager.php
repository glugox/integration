<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Event;

class Manager implements ManagerInterface {

    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /** @var \Glugox\Integration\Api\IntegrationServiceInterface */
    protected $_service;

    /** @var array */
    protected $_result;

    /** @var array */
    protected $_allIntegrations;

    /** @var int Current integration index */
    protected $_currentIntegrationIndex;

    /**
     * @param \Glugox\Integration\Helper\Data $helper
     * @param \Glugox\Integration\Api\IntegrationServiceInterface $service
     */
    public function __construct(\Glugox\Integration\Helper\Data $helper,
            \Glugox\Integration\Api\IntegrationServiceInterface $service) {
        $this->_helper = $helper;
        $this->_service = $service;
    }


    /**
     * Loads all integrations that we have.
     */
    private function _loadIntegrations() {

        $this->_helper->info("Loading Integrations...");

        // make sure the array is empty
        $this->_allIntegrations = array();

        // load all integrations from the database
        $integrations = $this->_service->getAllIntegrations();
        $this->_helper->info("Loaded " . \count($integrations) . ' Integrations!');
        foreach ($integrations as $integration) {
            $this->_helper->info($integration->getIntegrationCode());
            $this->_allIntegrations[] = $integration;
        }
    }


    /**
     * Run the integration
     *
     * @return array
     */
    public function run() {
        $this->_helper->info("Run");
        $this->start();
        return $this->_result;
    }


    /**
     * Starts the processes
     */
    public function start() {
        $this->_helper->info("Start");
        $this->_init();
        $result = $this->runNextImporter();
    }


    /**
     * Initializes the integration
     */
    private function _init() {
        $this->_helper->info("Init");

        // init data
        $this->_result = array(
            'error' => '',
            'current' => 0
        );
        $this->_currentIntegrationIndex = 0;
        $this->_loadIntegrations();

        //$this->_result['current'] = $this->_helper->getConfig('integration_activity/current');
    }


    /**
     * Runs the next importer in the queue
     *
     * @return boolean
     */
    protected function runNextImporter(){

        $this->_helper->info("Running the next importer...");

        /** @var \Glugox\Integration\Model\Integration **/
        $integration = isset($this->_allIntegrations[$this->_currentIntegrationIndex]) ? $this->_allIntegrations[$this->_currentIntegrationIndex] : null;
        ++$this->_currentIntegrationIndex;
        if(!$integration){
            $this->finishAll();
            return false;
        }
        $this->_helper->setConfig('integration_activity/current', $integration->getIntegrationCode());
        return $integration->import();
    }


    /**
     * Method to complete the importer cycle.
     */
    protected function finishAll(){
        $this->_helper->info("Integration completed!");
    }


    /**
     * Wether integration is running or not.
     *
     * @return type
     */
    public function isRunning() {
        return (int)$this->_helper->getConfig('integration_activity/current') !== 0;
    }


    /**
     *
     * @return boolean
     */
    public function isDisabled() {
        return $this->isRunning();
    }


}
