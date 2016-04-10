<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model\Integration\Import;

use Glugox\Integration\Model\Integration as IntegrationModel;

/**
 * Integration Importer Interface
 *
 * @api
 */
interface ImporterInterface {

    /**
     * Import external data from particular source
     *
     * @param array $importData
     * @throws \Glugox\Integration\Exception\IntegrationException
     */
    public function import(array $importData);

}
