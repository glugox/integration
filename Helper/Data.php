<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Glugox\Integration\Model\Integration\Import\Importer;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper {


    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry = null;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Framework\ObjectManagerInterface $objectManager,
            \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);

        $this->_objectManager = $objectManager;
        $this->_registry = $registry;
    }


    /**
     * @return Glugox\Integration\Helper\Config
     */
    public function getConfigObject() {
        return $this->_objectManager->get("Glugox\Integration\Helper\Config");
    }


    /**
     * Returns proper importer instance (Glugox\Integration\Model\Integration\Import\ImporterInterface)
     * based on the integration model (having data: integration_code, name, etc).
     *
     * @param type $integration
     * @return type
     */
    public function getImporter($integration){
        $className = 'Glugox\Integration\Model\Integration\Import\DefaultImporter';
        return $this->_objectManager->create($className)->setIntegration($integration);
    }

    /**
     * @return string
     */
    public function getMonitorUrl() {
        return $this->getConfigObject()->getMonitorUrl();
    }


    /**
     * @return string
     */
    public function getIntegrationAjaxUrl() {
        return $this->getConfigObject()->getIntegrationAjaxUrl();
    }


    /**
     *
     * @return boolean
     */
    public function isDisabled() {
        return $this->getManager()->isDisabled();
    }


    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger() {
        return $this->_logger;
    }


    /**
     * Integration manager instance
     * @return Glugox\Integration\Event\Manager
     */
    public function getManager() {
        return $this->_objectManager->get('Glugox\Integration\Event\Manager');
    }



    /**
     *
     * @param type $path
     * @return type
     */
    public function getConfig($path) {
        return $this->getConfigObject()->getConfig($path);
    }


    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = []) {
        return $this->getConfigObject()->getUrl($route, $params);
    }


    /**
     *
     * @param type $path
     * @param type $value
     * @return type
     */
    public function setConfig($path, $value) {
        return $this->getConfigObject()->setConfig($path, $value);
    }


    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function info($message, array $context = array()) {
        if (!is_string($message)) {
            $message = '<pre>' . print_r($message, true) . '</pre>';
        }

        // if we are running command, we have set the command output interface in the registry from that command
        if(null !== ($this->_registry->registry(\Glugox\Integration\Event\Manager::CURRENT_CMD_OUTPUT_INTERFACE))){
            $this->_registry->registry(\Glugox\Integration\Event\Manager::CURRENT_CMD_OUTPUT_INTERFACE)->writeln('<info>'.$message.'</info>');
        }

        return $this->_logger->info($message, $context);
    }



}
