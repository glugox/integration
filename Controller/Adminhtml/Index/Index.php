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
class Index extends Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /** @var \Psr\Log\LoggerInterface */
    protected $_logger;


    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->_logger = $logger;
    }

    /**
     * Check ACL.
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Glugox_Integration::integration');
    }
    
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
