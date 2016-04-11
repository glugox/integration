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
 * Description of DefaultImporter
 *
 * @author Eko
 */
class DefaultImporter extends Importer {

    /**
     *
     * @param array $importData
     */
    public function import() {
        $this->_helper->info("Importing (".$this->_integration->getIntegrationCode().")...");
        return true;
    }

}
