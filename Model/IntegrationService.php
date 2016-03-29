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
use Glugox\Integration\Model\Integration as IntegrationModel;
use Glugox\Integration\Exception\IntegrationException;

/**
 * Integration Service.
 *
 * This service is used to interact with integrations.
 */
class IntegrationService implements IntegrationServiceInterface {

    /**
     * @var IntegrationFactory
     */
    protected $_integrationFactory;

    /**
     * Construct and initialize Integration Factory
     *
     * @param IntegrationFactory $integrationFactory
     * @param IntegrationOauthService $oauthService
     */
    public function __construct(IntegrationFactory $objectManager) {
        $this->_integrationFactory = $objectManager;
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
    public function findActiveIntegration() {
        $integration = $this->_integrationFactory->create()->loadActiveIntegration();
        return $integration;
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

}
