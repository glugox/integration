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
 * @method \Glugox\Integration\Model\Integration setName(\string $name)
 * @method \string getSkuPrefix()
 * @method \Glugox\Integration\Model\Integration setSkuPrefix(\string $value)
 * @method \string getIntegrationCode()
 * @method \Glugox\Integration\Model\Integration setIntegrationCode(\string $name)
 * @method \string getIntegrationRunId()
 * @method \Glugox\Integration\Model\Integration setIntegrationRunId(\string $name)
 * @method \Glugox\Integration\Model\Integration setStatus(\int $value)
 * @method \string getCaFile()
 * @method \Glugox\Integration\Model\Integration setCaFile(\string $value)
 * @method \string getClientFile()
 * @method \Glugox\Integration\Model\Integration setClientFile(\string $value)
 * @method \string getKeyFile()
 * @method \Glugox\Integration\Model\Integration setCertPass(\string $value)
 * @method \string getCertPass()
 * @method \Glugox\Integration\Model\Integration setKeyFile(\string $value)
 * @method \string getCreatedAt()
 * @method \Glugox\Integration\Model\Integration setCreatedAt(\string $createdAt)
 * @method \string getUpdatedAt()
 * @method \Glugox\Integration\Model\Integration setUpdatedAt(\string $createdAt)
 * @method \string getImporterClass()
 * @method \Glugox\Integration\Model\Integration setImporterClass(\string $value)
 * @method \string getServiceUrl()
 * @method \Glugox\Integration\Model\ResourceModel\Integration getResource()
 */
class Integration extends \Magento\Framework\Model\AbstractModel {

    /*
     * Integration Status values
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /*
     * Integration Enabled values
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /*


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
     * Resets all integrations.
     *
     * @return array
     */
    public function resetAllIntegrations() {
        $integrations = $this->getResource()->resetAllIntegrations();
        return $integrations;
    }

    /**
     * Cleans all integration helper tables.
     */
    public function cleanHelperTables() {
        return $this->getResource()->cleanHelperTables();
    }

    /**
     * Returns number of records in the helper import ptoducts table.
     */
    public function getNumImportProductsLeft() {
        return $this->getResource()->getNumImportProductsLeft();
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
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function import(){
        return $this->_helper->getImporter($this)->import();
    }


}
