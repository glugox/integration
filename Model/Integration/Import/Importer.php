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
 * Description of Importer
 *
 * @author Eko
 */
abstract class Importer implements \Glugox\Integration\Model\Integration\Import\ImporterInterface {

    /** @var \Glugox\Integration\Helper\Data */
    protected $_helper;

    /** @var \Glugox\Integration\Model\Integration */
    protected $_integration;

    /**
     *
     * @param \Glugox\Integration\Model\Integration $integration
     * @return \Glugox\Integration\Model\Integration\Import\Importer
     */
    public function setIntegration(\Glugox\Integration\Model\Integration $integration){
        $this->_integration = $integration;
        return $this;
    }


    /**
     * @param \Glugox\Integration\Helper\Data $helper
     */
    public function __construct(\Glugox\Integration\Helper\Data $helper) {
        $this->_helper = $helper;
    }

    /**
     *
     * @return boolean
     */
    protected function validate(){
        return true;
    }


}
