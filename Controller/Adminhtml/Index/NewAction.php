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

use Glugox\Integration\Model\Integration as IntegrationModel;
use Glugox\Integration\Controller\Adminhtml\Index\Integration;

class NewAction extends Integration
{
    /**
     * New integration action.
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Glugox_Integration::integration');
        $this->_addBreadcrumb(__('New Integration'), __('New Integration'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('New Integration'));
        /** Try to recover integration data from session if it was added during previous request which failed. */
        $restoredIntegration = $this->_getSession()->getIntegrationData();
        if ($restoredIntegration) {
            $this->_registry->register(IntegrationModel::CURRENT_INTEGRATION_KEY, $restoredIntegration);
            $this->_getSession()->setIntegrationData([]);
        }
        $this->_view->renderLayout();
    }
}
