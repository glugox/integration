<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab;

use Glugox\Integration\Model\Integration as IntegrationModel;

/**
 * Main Integration info edit form
 *
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
    /*     * #@+
     * Form elements names.
     */

    const HTML_ID_PREFIX = 'glugox_integration_properties_';
    const DATA_ID = 'integration_id';
    const DATA_INTEGRATION_CODE = 'integration_code';
    const DATA_NAME = 'name';
    const SKU_PREFIX = 'sku_prefix';
    const DATA_STATUS = 'status';
    const DATA_ENABLED = 'enabled';
    const DATA_SERVICE_URL = 'service_url';
    const DATA_IMPORTER_CLASS = 'importer_class';

    const DATA_CA_FILE = 'ca_file';
    const DATA_CLIENT_FILE = 'client_file';
    const DATA_KEY_FILE = 'key_file';
    const DATA_CERT_PASS = 'cert_pass';

    /*     * #@- */

    /**
     * Set form id prefix, declare fields for integration info
     *
     * @return $this
     */
    protected function _prepareForm() {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix(self::HTML_ID_PREFIX);
        $integrationData = $this->_coreRegistry->registry(IntegrationModel::CURRENT_INTEGRATION_KEY);
        $this->_addGeneralFieldset($form, $integrationData);
        $form->setValues($integrationData);
        $this->setForm($form);
        return $this;
    }


    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel() {
        return __('Integration Info');
    }


    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return $this->getTabLabel();
    }


    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab() {
        return true;
    }


    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden() {
        return false;
    }


    /**
     * Add fieldset with general integration information.
     *
     * @param \Magento\Framework\Data\Form $form
     * @param array $integrationData
     * @return void
     */
    protected function _addGeneralFieldset($form, $integrationData) {
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);


        $disabled = isset($integrationData[Main::DATA_STATUS]) &&  (int)$integrationData[Main::DATA_STATUS] == IntegrationModel::STATUS_ACTIVE;
        if (isset($integrationData[self::DATA_ID])) {
            $fieldset->addField(self::DATA_ID, 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
                self::DATA_INTEGRATION_CODE, 'text', [
            'label' => __('Code'),
            'name' => self::DATA_INTEGRATION_CODE,
            'required' => true,
            'disabled' => $disabled,
            'maxlength' => '255'
                ]
        );

        $fieldset->addField(
                self::DATA_NAME, 'text', [
            'label' => __('Name'),
            'name' => self::DATA_NAME,
            'required' => true,
            'disabled' => $disabled,
            'maxlength' => '255'
                ]
        );

        $fieldset->addField(
                self::SKU_PREFIX, 'text', [
            'label' => __('SKU Prefix'),
            'name' => self::SKU_PREFIX,
            'required' => false,
            'disabled' => $disabled,
            'maxlength' => '10'
                ]
        );

        $fieldset->addField(
                self::DATA_SERVICE_URL, 'text', [
            'label' => __('Service URL'),
            'name' => self::DATA_SERVICE_URL,
            'required' => true,
            'disabled' => $disabled,
            'note' => __(
                    'Main service URL'
            )
                ]
        );

        $fieldset->addField(
                self::DATA_IMPORTER_CLASS, 'text', [
            'label' => __('Importer Class'),
            'name' => self::DATA_IMPORTER_CLASS,
            'disabled' => $disabled,
            'maxlength' => '255',
            'note' => __(
                    'Class that extends abstract class Glugox\Integration\Model\Integration\Import\Importer, and can be used for custom imports.'
            )
                ]
        );

        $fieldset->addField(
                self::DATA_ENABLED, 'select', [
            'name' => self::DATA_ENABLED,
            'disabled' => $disabled,
            'label' => __('Enabled'),
            'options' => [
                \Glugox\Integration\Model\Integration::STATUS_DISABLED => __('Disabled'),
                \Glugox\Integration\Model\Integration::STATUS_ENABLED => __('Enabled'),
            ]
                ]
        );
    }


}
