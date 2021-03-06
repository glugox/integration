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

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Config extends AbstractHelper {


    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\App\Route\Config
     */
    protected $_routeConfig;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $_locale;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var string
     */
    protected $_ajaxUrl;

    /**
     * @var string
     */
    protected $_monitorUrl;

    /**
     * @var array
     */
    private $_configCache;

    /**
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Route\Config $routeConfig
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            \Magento\Framework\App\Route\Config $routeConfig,
            \Magento\Framework\Locale\ResolverInterface $locale,
            \Magento\Backend\Model\UrlInterface $backendUrl,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Config\Model\ResourceModel\Config $resourceConfig
            ) {
        parent::__construct($context);

        $this->_scopeConfig = $scopeConfig;
        $this->_routeConfig = $routeConfig;
        $this->_locale = $locale;
        $this->_backendUrl = $backendUrl;
        $this->_resourceConfig = $resourceConfig;

        $this->_configCache = array();
    }


    /**
     * @return string
     */
    public function getMonitorUrl() {
        if (!$this->_monitorUrl) {
            $this->_monitorUrl = $this->getUrl("*/monitor");
        }
        return $this->_monitorUrl;
    }


    /**
     * @return string
     */
    public function getIntegrationAjaxUrl() {
        if (!$this->_ajaxUrl) {
            $this->_ajaxUrl = $this->getUrl("*/ajax");
        }
        return $this->_ajaxUrl;
    }


    /**
     * Get config for this module.
     *
     * To avoid config reinitialization and stores reinitialization,
     * we are just setting new config values to cache if we need to get it back
     * in the same request.
     *
     * @param type $path
     * @return type
     */
    public function getConfig($path) {
        if (isset($this->_configCache[$path])) {
            return $this->_configCache[$path];
        }
        return $this->_scopeConfig->getValue('glugox/' . $path, 'default');
    }


    /**
     * Set config for this module.
     *
     * To avoid config reinitialization and stores reinitialization,
     * we are just setting new config values to cache if we need to get it back
     * in the same request.
     *
     * @param type $path
     * @return Glugox\Integration\Helper\Config
     */
    public function setConfig($path, $value) {
        $this->_configCache[$path] = $value;
        $this->_resourceConfig->saveConfig('glugox/' . $path, $value, 'default', 0);
        return $this;
    }


    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = []) {
        return $this->_backendUrl->getUrl($route, $params);
    }


    /**
     * @return string
     */
    public function getImporterWithCertClass(){
        return '\Glugox\Integration\Model\Integration\Import\ImporterWithCert';
    }


}
