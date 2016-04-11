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
use Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab\Main;

/**
 * Security Integration info edit form
 *
 */
class Security extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /*
     * Form elements names.
     */


    /**
     * Set form id prefix, declare fields for integration info
     *
     * @return $this
     */
    protected function _prepareForm() {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix(Main::HTML_ID_PREFIX);
        $integrationData = $this->_coreRegistry->registry(IntegrationModel::CURRENT_INTEGRATION_KEY);
        $this->_addSecurityFieldset($form, $integrationData);
        $form->setValues($integrationData);
        $this->setForm($form);

        return parent::_prepareForm();
    }


    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel() {
        return __('Integration Security');
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
    protected function _addSecurityFieldset($form, $integrationData) {
        $fieldset = $form->addFieldset('security_fieldset', ['legend' => __('Security')]);
        $disabled = isset($integrationData[Main::DATA_STATUS]) &&  (int)$integrationData[Main::DATA_STATUS] == IntegrationModel::STATUS_ACTIVE;

        $fieldset->addField(
                Main::DATA_CA_FILE, 'text', [
            'label' => __('CA File'),
            'name' => Main::DATA_CA_FILE,
            'disabled' => $disabled,
            'note' => __(
                    'CA File'
            )
                ]
        );

        $fieldset->addField(
                Main::DATA_CLIENT_FILE, 'text', [
            'label' => __('Client File'),
            'name' => Main::DATA_CLIENT_FILE,
            'disabled' => $disabled,
            'note' => __(
                    'Client File'
            )
                ]
        );

        $fieldset->addField(
                Main::DATA_KEY_FILE, 'text', [
            'label' => __('Key File'),
            'name' => Main::DATA_KEY_FILE,
            'disabled' => $disabled,
            'note' => __(
                    'Key File'
            )
                ]
        );

        $fieldset->addField(
                Main::DATA_CERT_PASS, 'text', [
            'label' => __('Cert Pass'),
            'name' => Main::DATA_CERT_PASS,
            'disabled' => $disabled,
            'note' => __(
                    'Cert Pass'
            )
                ]
        );


    }


}
