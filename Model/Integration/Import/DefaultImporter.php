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
        $this->_helper->info($this->_integration->getData());

        // validate
        $validation = $this->validate();
        if(true !== $validation){
            $this->_helper->info(" - " . $validation);
        }else{

            // mark this integration as active/running
            $this->_integration->setStatus(\Glugox\Integration\Model\Integration::STATUS_ACTIVE)->save();

            $this->_helper->info(" - done!");
        }

        // mark this integration as inactive/finished
        $this->_integration->setStatus(\Glugox\Integration\Model\Integration::STATUS_INACTIVE)->save();

        return true;
    }

}
