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

use Magento\Backend\App\Action;
use Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab\Main;
use Glugox\Integration\Exception\IntegrationException;
use Glugox\Integration\Controller\Adminhtml\Index\Integration;
use Glugox\Integration\Model\Integration as IntegrationModel;

class Edit extends Integration
{
    
    /**
     * Edit integration action.
     *
     * @return void
     */
    public function execute()
    {
        /** Try to recover integration data from session if it was added during previous request which failed. */
        $integrationId = (int)$this->getRequest()->getParam('id');
        if ($integrationId) {
            try {
                $integrationData = $this->_integrationService->get($integrationId)->getData();
                $originalName = $this->escaper->escapeHtml($integrationData[Main::DATA_NAME]);
            } catch (IntegrationException $e) {
                $this->messageManager->addError($this->escaper->escapeHtml($e->getMessage()));
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->_logger->critical($e);
                $this->messageManager->addError(__('Internal error. Check exception log for details.'));
                $this->_redirect('*/*');
                return;
            }
            $restoredIntegration = $this->_getSession()->getIntegrationData();
            if (isset($restoredIntegration[Main::DATA_ID]) && $integrationId == $restoredIntegration[Main::DATA_ID]) {
                $integrationData = array_merge($integrationData, $restoredIntegration);
            }
        } else {
            $this->messageManager->addError(__('Integration ID is not specified or is invalid.'));
            $this->_redirect('*/*/');
            return;
        }
        $this->_registry->register(IntegrationModel::CURRENT_INTEGRATION_KEY, $integrationData);
        $this->_view->loadLayout();
        $this->_getSession()->setIntegrationData([]);
        $this->_setActiveMenu('Glugox_Integration::integration');

        $title = __('Edit "%1" Integration', $originalName);

        $this->_addBreadcrumb($title, $title);
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
}
