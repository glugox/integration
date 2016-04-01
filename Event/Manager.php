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

    /**
     * @param \Glugox\Integration\Helper\Data $integrationHelper
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
        $integrations = $this->_service->getAllIntegrations();
        $this->_helper->info("Loaded " . \count($integrations) . ' Integrations!');
        foreach ($integrations as $integration) {
            $this->_helper->info($integration->getIntegrationCode());
        }
    }


    /**
     * Run the integration
     */
    public function run() {
        $this->_helper->setConfig('integration_activity/current', 3);
        $this->_result = array(
            'error' => '',
            'current' => 0
        );
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
    }


    /**
     * Initializes the integration
     */
    private function _init() {
        $this->_helper->info("Init");
        $this->_loadIntegrations();

        $this->_result['current'] = $this->_helper->getConfig('integration_activity/current');
    }


    /**
     * Wether integration is running or not.
     *
     * @return type
     */
    public function isRunning() {
        return (int) $this->_helper->getConfig('integration_activity/current') !== 0;
    }


    /**
     *
     * @return boolean
     */
    public function isDisabled() {
        return $this->isRunning();
    }


}
