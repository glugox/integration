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
class Data extends AbstractHelper {

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
    protected $_monitorUrl;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Route\Config $routeConfig
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Backend\Model\Auth $auth
     * @param \Magento\Backend\App\Area\FrontNameResolver $frontNameResolver
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Route\Config $routeConfig, \Magento\Framework\Locale\ResolverInterface $locale, \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        parent::__construct($context);
        $this->_routeConfig = $routeConfig;
        $this->_locale = $locale;
        $this->_backendUrl = $backendUrl;
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
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = []) {
        return $this->_backendUrl->getUrl($route, $params);
    }


}
