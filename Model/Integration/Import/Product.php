<?php

/*
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Model\Integration\Import;

/**
 * Importing Product Model.
 *
 * @method \int getImporterCode()
 * @method \Glugox\Integration\Model\Integration\Import\Product setImporterCode(\int $value)
 * @method \string getSku()
 * @method \Glugox\Integration\Model\Integration\Import\Product setSku(\string $value)
 * @method \string getCategory()
 * @method \Glugox\Integration\Model\Integration\Import\Product setCategory(\string $value)
 * @method \string getName()
 * @method \Glugox\Integration\Model\Integration\Import\Product setName(\string $value)
 * @method \string getWarranty()
 * @method \Glugox\Integration\Model\Integration\Import\Product setWarranty(\string $value)
 * @method \string getBrend()
 * @method \Glugox\Integration\Model\Integration\Import\Product setBrend(\string $value)
 * @method \int getQttyinstock()
 * @method \Glugox\Integration\Model\Integration\Import\Product setQttyinstock(\int $value)
 * @method \decimal getTax()
 * @method \Glugox\Integration\Model\Integration\Import\Product setTax(\decimal $value)
 * @method \decimal getPrice()
 * @method \Glugox\Integration\Model\Integration\Import\Product setPrice(\decimal $value)
 * @method \decimal getCost()
 * @method \Glugox\Integration\Model\Integration\Import\Product setCost(\decimal $value)
 * @method \string getDescription()
 * @method \Glugox\Integration\Model\Integration\Import\Product setDescription(\string $value)
 * @method \string getShortDescription()
 * @method \Glugox\Integration\Model\Integration\Import\Product setShortDescription(\string $value)
 * @method \string getImageUrl()
 * @method \Glugox\Integration\Model\Integration\Import\Product setImageUrl(\string $value)
 * @method \decimal getSpecialOffer()
 * @method \Glugox\Integration\Model\Integration\Import\Product setSpecialOffer(\decimal $value)
 * @method \string getTimeChanged()
 * @method \Glugox\Integration\Model\Integration\Import\Product setTimeChanged(\string $value)
 * @method \int getInvalidated()
 * @method \Glugox\Integration\Model\Integration\Import\Product setInvalidated(\int $value)
 */
class Product extends \Magento\Framework\Model\AbstractModel {

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize Model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Glugox\Integration\Model\ResourceModel\Integration\Import\Product');
    }
}
