<?php

/*
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Model;

/**
 * Description of ImportResult
 *
 * @author Glugox <glugox@gmail.com>
 */
class ImportResult extends \Magento\Framework\DataObject {

    /**
     * Integration id
     *
     * @var int
     */
    public $integrationId;

    /**
     * Started at time
     *
     * @var string
     */
    public $startedAt;

    /**
     * Finished at time
     *
     * @var string
     */
    public $finishedAt;

    /**
     * Array to store created products
     *
     * @var array
     */
    public $createdProducts = [];

    /**
     * Array to store updated products
     *
     * @var array
     */
    public $updatedProducts = [];

    /**
     * Array to store disabled products
     *
     * @var array
     */
    public $disabledProducts = [];

    /**
     * Array to store import errors
     *
     * @var array
     */
    public $errors = [];

    /**
     * Array to store import messages (with errors)
     *
     * @var array
     */
    public $messages = [];

    /**
     *
     * @param type $msg
     * @param type $isError
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addMessage($msg, $isError = false) {

        if ($isError) {
            $this->errors[] = $msg;
        }
        $this->messages[] = $msg;

        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductCreated($sku) {
        $this->createdProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductUpdated($sku) {
        $this->updatedProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @param string $sku
     * @return \Glugox\Integration\Model\ImportResult
     */
    public function addProductDisabled($sku) {
        $this->disabledProducts[] = $sku;
        return $this;
    }


    /**
     *
     * @return boolean
     */
    public function hasErrors() {
        return \count($this->errors) > 0;
    }


    /**
     *
     * @return boolean
     */
    public function isSuccess() {
        return !$this->hasErrors();
    }


    /**
     * Returns summary for the import
     *
     * @param boolean $detailed
     * @return string
     */
    public function getSummary($detailed = true) {

        $this->setData("numErrors", \count($this->errors));
        $this->setData("numCreatedProducts", \count($this->createdProducts));
        $this->setData("numUpdatedProducts", \count($this->updatedProducts));
        $this->setData("numDisabledProducts", \count($this->disabledProducts));

        if ($detailed) {
            $this->setData("createdProducts", $this->createdProducts);
            $this->setData("updatedProducts", $this->updatedProducts);
            $this->setData("disabledProducts", $this->disabledProducts);
            $this->setData("messages", $this->messages);
        } else {
            $this->setData("errors", $this->errors);
        }
        return $this->toJson();
    }


}
