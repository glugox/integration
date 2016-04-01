<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Api;

use Glugox\Integration\Model\Integration as IntegrationModel;

/**
 * Integration Service Interface
 *
 * @api
 */
interface IntegrationServiceInterface {

    /**
     * Create a new Integration
     *
     * @param array $integrationData
     * @return IntegrationModel
     * @throws \Glugox\Integration\Exception\IntegrationException
     */
    public function create(array $integrationData);

    /**
     * Get the details of a specific Integration.
     *
     * @param int $integrationId
     * @return IntegrationModel
     * @throws \Glugox\Integration\Exception\IntegrationException
     */
    public function get($integrationId);

    /**
     * Find Integration by code.
     *
     * @param string $integrationCode
     * @return IntegrationModel
     */
    public function findByCode($integrationCode);

    /**
     * Get the details of an active Integration.
     *
     * @return IntegrationModel
     */
    public function selectActiveIntegration();

    /**
     * Gets all Integrations.
     *
     * @return array
     */
    public function getAllIntegrations();

    /**
     * Gets all Integrations as array of associative arrays data.
     *
     * @return array
     */
    public function getAllIntegrationRows();

    /**
     * Update an Integration.
     *
     * @param array $integrationData
     * @return IntegrationModel
     * @throws \Glugox\Integration\Exception\IntegrationException
     */
    public function update(array $integrationData);

    /**
     * Delete an Integration.
     *
     * @param int $integrationId
     * @return array Integration data
     * @throws \Glugox\Integration\Exception\IntegrationException
     */
    public function delete($integrationId);
}
