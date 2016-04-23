<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Controller\Adminhtml\Monitor;

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

        $datetime = $this->getRequest()->getParam("fromTime", null);
        $messages = $this->_service->getImportLogMessagesFrom($datetime);

        if(!\count($messages)){
            $messages = ["delete-all"];
        }

        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode(
                         $messages
                    )
            );
        } else {
            $this->_redirect('*/*/');
        }
    }


}
