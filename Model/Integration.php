<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model;

/**
 * Integration model.
 *
 * @method \string getName()
 * @method Integration setName(\string $name)
 * @method \string getIntegrationCode()
 * @method Integration setIntegrationCode(\string $name)
 * @method Integration setStatus(\int $value)
 * @method \string getCreatedAt()
 * @method Integration setCreatedAt(\string $createdAt)
 * @method \string getUpdatedAt()
 * @method Integration setUpdatedAt(\string $createdAt)
 * @method \Glugox\Integration\Model\ResourceModel\Integration getResource()
 */
class Integration extends \Magento\Framework\Model\AbstractModel {
    /*     * #@+
     * Integration Status values
     */

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /*     * #@- */


    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /*     * #@+
     * Integration data key constants.
     */
    const ID = 'integration_id';
    const NAME = 'name';
    const CODE = 'integration_code';
    const SERVICE_URL = 'service_url';
    const STATUS = 'status';
    const CURRENT_INTEGRATION_KEY = 'current_glugox_integration';

    /*     * #@- */

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
            \Magento\Framework\Model\Context $context,
            \Magento\Framework\Registry $registry,
            \Glugox\Integration\Helper\Data $helper,
            \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
            \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
            array $data = []
    ) {

        $this->_helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->_init('Glugox\Integration\Model\ResourceModel\Integration');
    }


    /**
     * Load integration by distributer code.
     *
     * @param string $distributerKey
     * @return $this
     */
    public function loadByCode($distributerCode) {
        return $this->load($distributerCode, self::CODE);
    }


    /**
     * Load active integration.
     *
     * @param int $consumerId
     * @return $this
     */
    public function selectActiveIntegration() {
        $integrationData = $this->getResource()->selectActiveIntegration();
        $this->setData($integrationData ? $integrationData : []);
        return $this;
    }


    /**
     * Returns all integrations.
     *
     * @return array
     */
    public function getAllIntegrations() {
        $integrations = $this->getResource()->getAllIntegrations();
        return $integrations;
    }


    /**
     * Get integration status.
     *
     * @return int
     * @api
     */
    public function getStatus() {
        return (int) $this->getData(self::STATUS);
    }


    /**
     *
     * @return boolean
     */
    public function import(){
        return $this->_helper->getImporter($this)->import();
    }


}
