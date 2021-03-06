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

use Glugox\Integration\Model\Integration;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\InputDefinition;

class Manager implements ManagerInterface {

    /** @var string */
    const CURRENT_CMD_OUTPUT_INTERFACE = 'currcmd_output_interface';

    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /** @var \Glugox\Integration\Api\IntegrationServiceInterface */
    protected $_service;

    /** @var \Symfony\Component\Console\Input\StringInput */
    protected $_input;

    /** @var array */
    protected $_result;

    /** @var array */
    protected $_allIntegrations;

    /** @var int Current integration index */
    protected $_currentIntegrationIndex;

    /** @var bool New cycle means we will import new data from sources to helper tables and start importing into magento db, false means we continue where we left */
    protected $_isNewCycle = true;

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
     * Loads all integrations that we have and prepares them for imports.
     *
     * @param boolean $starting Marker if we are actually staring the integration, so throw error if there are running ones.
     */
    private function _prepareIntegrations() {

        $this->_helper->info("Loading Integrations...");
        $hasIntegrationRunning = false;

        // make sure the array is empty
        $this->_allIntegrations = array();

        // load all integrations from the database
        $integrations = $this->_service->getAllIntegrations();
        $this->_helper->info("Loaded " . \count($integrations) . ' Integrations:');
        foreach ($integrations as $integration) {
            $this->_helper->info(" - " . $integration->getIntegrationCode());
            $this->_allIntegrations[] = $integration;
            $hasIntegrationRunning = $hasIntegrationRunning || (Integration::STATUS_ACTIVE === (int) $integration->getStatus());
        }

        $isForce = $this->_input->getOption(\Glugox\Integration\Console\Command\ImportCommand::FORCE);
        $isReset = $this->_input->getOption(\Glugox\Integration\Console\Command\ImportCommand::RESET);

        $numImportProductsLeft = $this->_service->getNumImportProductsLeft();
        $this->_isNewCycle = 0 === $numImportProductsLeft;

        if ($hasIntegrationRunning) {
            throw new \Glugox\Integration\Exception\IntegrationException(__('One of the integrations is already running! You must restart apache to stop the script first.'));
        }

        if($isReset){
            $this->resetAllIntegrations();
            $this->_isNewCycle = true;
        }

    }


    /**
     * Resets all integrations
     */
    public function resetAllIntegrations() {

        // reset db tables
        $reset = $this->_service->resetAllIntegrations();
        $reset = $this->_service->cleanHelperTables();
    }


    /**
     * Run the integration
     *
     * @param string $input Command input
     * @return array
     */
    public function run($strInput = "", InputDefinition $definition = null) {
        $this->_input = new StringInput($strInput, $definition);
        $this->start();
        return $this->_result;
    }


    /**
     * Starts the processes
     */
    public function start() {
        $this->_init();

        if($this->_isNewCycle){

            $result = $this->runNextImporter();
            while (false !== $result) {
                $result = $this->runNextImporter();
            }
        }

        // continue with creating/updating real magento products...

    }


    /**
     * Initializes the integration
     */
    private function _init() {
        // init data
        $this->_result = array(
            'error' => '',
            'current' => 0
        );
        $this->_currentIntegrationIndex = 0;
        $this->_prepareIntegrations();
    }


    /**
     * Returns the next integration from the queue
     *
     * @return  \Glugox\Integration\Model\Integration|null
     */
    private function _getNextIntegration($checkAvailability = true) {
        /** @var \Glugox\Integration\Model\Integration * */
        $integration = isset($this->_allIntegrations[$this->_currentIntegrationIndex]) ? $this->_allIntegrations[$this->_currentIntegrationIndex] : null;
        ++$this->_currentIntegrationIndex;

        if ($integration && $checkAvailability) {
            $availability = (int) $integration->getEnabled();

            // if integration is not enabled, loop untill we find enabled one,
            // or untill we get out of integrations
            while (Integration::STATUS_ENABLED !== $availability && $integration) {
                $integration = isset($this->_allIntegrations[$this->_currentIntegrationIndex]) ? $this->_allIntegrations[$this->_currentIntegrationIndex] : null;
                ++$this->_currentIntegrationIndex;
            }
        }

        return $integration;
    }


    /**
     * Runs the next importer in the queue
     *
     * @return \Glugox\Integration\Model\ImportResult
     */
    protected function runNextImporter() {

        // check completion
        if (!$integration = $this->_getNextIntegration()) {
            $this->finishAll();
            return false;
        }

        $this->_helper->info("Running the next available importer...");
        $this->_helper->setConfig('integration_activity/current', $integration->getIntegrationCode());

        return $integration->import();
    }


    /**
     * Method to complete the importer cycle.
     */
    protected function finishAll() {
        $this->_helper->setConfig('integration_activity/current', null);
        $this->_helper->info("Integration completed!");
    }


    /**
     * Wether integration is running or not.
     *
     * @return type
     */
    public function isRunning() {
        return !empty($this->_helper->getConfig('integration_activity/current'));
    }


    /**
     *
     * @return boolean
     */
    public function isDisabled() {
        return $this->isRunning();
    }


}
