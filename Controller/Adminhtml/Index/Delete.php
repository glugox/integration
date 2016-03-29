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

use Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab\Main;
use Glugox\Integration\Exception\IntegrationException;
use Magento\Framework\Controller\ResultFactory;
use Glugox\Integration\Model\Integration as IntegrationModel;

class Delete extends \Glugox\Integration\Controller\Adminhtml\Index\Integration
{
    /**
     * Delete the integration.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $integrationId = (int)$this->getRequest()->getParam('id');
        try {
            if ($integrationId) {
                $integrationData = $this->_integrationService->delete($integrationId);
                if (!$integrationData[Main::DATA_ID]) {
                    $this->messageManager->addError(__('This integration no longer exists.'));
                } else {
                    $this->_registry->register(IntegrationModel::CURRENT_INTEGRATION_KEY, $integrationData);
                    $this->messageManager->addSuccess(
                        __(
                            "The integration '%1' has been deleted.",
                            $this->escaper->escapeHtml($integrationData[Main::DATA_NAME])
                        )
                    );
                }
            } else {
                $this->messageManager->addError(__('Integration ID is not specified or is invalid.'));
            }
        } catch (IntegrationException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
