<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Controller\Adminhtml\Ajax;

use Glugox\Integration\Controller\Adminhtml\Index\Integration;

/**
 * Controller for integrations management.
 */
class Index extends Integration {

    /**
     * Integrations grid.
     *
     * @return void
     */
    public function execute() {

        $command = new \Glugox\Integration\Console\Command\ImportCommand($this->_manager, $this->_registry);

        $result = $this->_manager->run("", $command->getDefinition());
        if ($this->getRequest()->isXmlHttpRequest()) {
            $jsonResult = $this->jsonHelper->jsonEncode($result);
            $this->getResponse()->representJson($jsonResult);
        } else {
            $this->_redirect('*/*/');
        }
    }


}
