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
abstract class Integration extends Action {

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /** @var \Glugox\Integration\Event\Manager */
    protected $_manager;

    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /** @var \Glugox\Integration\Api\IntegrationServiceInterface */
    protected $_service;

    /** @var \Magento\Framework\Json\Helper\Data */
    protected $jsonHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Glugox\Integration\Event\Manager $manager
     * @param \Glugox\Integration\Helper\Data $helper
     * @param \Glugox\Integration\Api\IntegrationServiceInterface $service
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
    \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\Registry $registry,
            \Glugox\Integration\Event\Manager $manager,
            \Glugox\Integration\Helper\Data $helper,
            \Glugox\Integration\Api\IntegrationServiceInterface $service,
            \Magento\Framework\Json\Helper\Data $jsonHelper,
            \Magento\Framework\Escaper $escaper
    ) {
        parent::__construct($context);
        $this->_registry = $registry;
        $this->_manager = $manager;
        $this->_helper = $helper;
        $this->_service = $service;
        $this->jsonHelper = $jsonHelper;
        $this->escaper = $escaper;
    }


    /**
     * Check ACL.
     *
     * @return boolean
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Glugox_Integration::integration');
    }


    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger() {
        return $this->_helper->getLogger();
    }


}
