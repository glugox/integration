<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Controller\Adminhtml\Index;

use Glugox\Integration\Exception\IntegrationException;
use Glugox\Integration\Model\Integration as IntegrationModel;

class Start extends \Glugox\Integration\Controller\Adminhtml\Index\Integration {

    /**
     * Starts the integration.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute() {

        $result = [
            'current_integration' => 0,
            'current_integration_progress' => 0,
            'overall_integration_progress' => 0,
        ];
        $integrationId = (int) $this->getRequest()->getParam('id');
        try {
            if ($integrationId) {
                $integrationData = $this->_service->get($integrationId);
                if (!$integrationData[Main::DATA_ID]) {
                    $this->messageManager->addError(__('This integration no longer exists.'));
                } else {
                    //
                }
            } else {
                // we are running all integrations
            }
        } catch (IntegrationException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->getLogger()->critical($e);
        }
    }


}
