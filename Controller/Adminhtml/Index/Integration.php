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
abstract class Integration extends Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /** @var \Psr\Log\LoggerInterface */
    protected $_logger;
    
    /** @var \Glugox\Integration\Api\IntegrationServiceInterface */
    protected $_integrationService;

    /** @var \Magento\Framework\Json\Helper\Data */
    protected $jsonHelper;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Glugox\Integration\Api\IntegrationServiceInterface $integrationService
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $logger,
        \Glugox\Integration\Api\IntegrationServiceInterface $integrationService,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Escaper $escaper
    ) {
        parent::__construct($context);
        $this->_registry = $registry;
        $this->_logger = $logger;
        $this->_integrationService = $integrationService;
        $this->jsonHelper = $jsonHelper;
        $this->escaper = $escaper;
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
    
    
}
