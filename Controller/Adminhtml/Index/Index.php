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

/**
 * Controller for integrations management.
 */
class Index extends Integration
{

    /**
     * Integrations grid.
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Glugox_Integration::integration');
        $this->_addBreadcrumb(__('Glugox Integration'), __('Integration'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Integration'));
        $this->_view->renderLayout();
    }
}
