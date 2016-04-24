<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model;

use Glugox\Integration\Api\IntegrationServiceInterface;
use Glugox\Integration\Model\IntegrationFactory;
use Glugox\Integration\Model\Integration\LogFactory;
use Glugox\Integration\Model\Integration\ResultFactory;
use Glugox\Integration\Model\Integration as IntegrationModel;
use Glugox\Integration\Exception\IntegrationException;

/**
 * Integration Service.
 *
 * This service is used to interact with integrations.
 */
class IntegrationService implements IntegrationServiceInterface {


    /**
     * When integrations resets, these tables are being reseted/truncated
     */
    const TRUNCATEABLE_TABLES = ["glugox_import_products"];

    /**
     * @var IntegrationFactory
     */
    protected $_integrationFactory;


    /**
     * @var LogFactory
     */
    protected $_logFactory;

    /**
     * @var ResultFactory
     */
    protected $_resultFactory;

    /**
     * Construct and initialize Integration Factory
     *
     * IntegrationService constructor.
     * @param \Glugox\Integration\Model\IntegrationFactory $objectManager
     * @param LogFactory $logFactory
     * @param ResultFactory $resultFactory
     */
    public function __construct(
            IntegrationFactory $objectManager,
            LogFactory $logFactory,
            ResultFactory $resultFactory) {
        $this->_integrationFactory = $objectManager;
        $this->_logFactory = $logFactory;
        $this->_resultFactory = $resultFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function create(array $integrationData) {
        $this->_checkIntegrationByName($integrationData['name']);
        $integration = $this->_integrationFactory->create()->setData($integrationData);
        $integration->save();
        return $integration;
    }


    /**
     * {@inheritdoc}
     */
    public function update(array $integrationData) {
        $integration = $this->_loadIntegrationById($integrationData['integration_id']);
        //If name has been updated check if it conflicts with an existing integration
        if ($integration->getName() != $integrationData['name']) {
            $this->_checkIntegrationByName($integrationData['name']);
        }
        $integration->addData($integrationData);
        $integration->save();
        return $integration;
    }


    /**
     * {@inheritdoc}
     */
    public function delete($integrationId) {
        $integration = $this->_loadIntegrationById($integrationId);
        $data = $integration->getData();
        $integration->delete();
        return $data;
    }


    /**
     * {@inheritdoc}
     */
    public function get($integrationId) {
        $integration = $this->_loadIntegrationById($integrationId);
        return $integration;
    }


    /**
     * {@inheritdoc}
     */
    public function findByName($name) {
        $integration = $this->_integrationFactory->create()->load($name, 'name');
        return $integration;
    }


    /**
     * {@inheritdoc}
     */
    public function findByCode($integrationCode) {
        $integration = $this->_integrationFactory->create()->load($integrationCode, 'code');
        return $integration;
    }


    /**
     * {@inheritdoc}
     */
    public function selectActiveIntegration() {
        $integration = $this->_integrationFactory->create()->selectActiveIntegration();
        return $integration;
    }


    /**
     * @return array
     */
    public function getAllIntegrations() {
        $integrations = $this->_integrationFactory->create()->getCollection();
        return $integrations;
    }


    /**
     * @return array
     */
    public function getAllIntegrationRows() {
        $integrations = $this->_integrationFactory->create()->getAllIntegrations();
        return $integrations;
    }

    /**
     * @return array
     */
    public function resetAllIntegrations() {
        $integrations = $this->_integrationFactory->create()->resetAllIntegrations();
        return $integrations;
    }

    /**
     * @return type
     */
    public function cleanHelperTables() {
        return $this->_integrationFactory->create()->cleanHelperTables();
    }

    /**
     * Returns number of records in the helper import ptoducts table
     *
     * @return int
     */
    public function getNumImportProductsLeft() {
        return $this->_integrationFactory->create()->getNumImportProductsLeft();
    }


    /**
     * Check if an integration exists by the name
     *
     * @param string $name
     * @return void
     * @throws Glugox\Integration\Exception\IntegrationException
     */
    private function _checkIntegrationByName($name) {
        $integration = $this->_integrationFactory->create()->load($name, 'name');
        if ($integration->getId()) {
            throw new IntegrationException(__('Integration with name \'%1\' exists.', $name));
        }
    }


    /**
     * Load integration by id.
     *
     * @param int $integrationId
     * @return IntegrationModel
     * @throws Glugox\Integration\Exception\IntegrationException
     */
    protected function _loadIntegrationById($integrationId) {
        $integration = $this->_integrationFactory->create()->load($integrationId);
        if (!$integration->getId()) {
            throw new IntegrationException(__('Integration with ID \'%1\' does not exist.', $integrationId));
        }
        return $integration;
    }


    /**
     * Returns messages from the import log table.
     * If $fromTime argument is passed, it returns only messages at and after that time.
     *
     * @param string $fromTime Msyql datetime format string
     * @param string $integrationRunId Select only from specific integration run, if null -> any
     * @return array
     */
    public function getImportLogMessagesFrom($fromTime, $integrationRunId=null) {
        $integrationRunId = $this->_resultFactory->create()->getLastResultRunId();
        $log = $this->_logFactory->create()->getImportLogMessagesFrom($fromTime, $integrationRunId);
        return $log;
    }




}
