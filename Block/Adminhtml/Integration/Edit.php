<?php

/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Glugox\Integration\Block\Adminhtml\Integration;

use Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab\Main;
use Glugox\Integration\Controller\Adminhtml\Integration;
use Glugox\Integration\Model\Integration as IntegrationModel;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry = null;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Widget\Context $context, \Magento\Framework\Registry $registry, array $data = []
    ) {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }


    /**
     * Initialize Integration edit page
     *
     * @return void
     */
    protected function _construct() {
        $this->_controller = 'adminhtml_integration';
        $this->_blockGroup = 'Glugox_Integration';
        parent::_construct();
        $this->buttonList->remove('reset');
        $this->buttonList->remove('delete');


        if ($this->_isNewIntegration()) {
            $this->removeButton(
                    'save'
            )->addButton(
                    'save', [
                'id' => 'save-split-button',
                'label' => __('Save'),
                'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
                'button_class' => '',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']],
                ],
                'options' => [
                    'save_activate' => [
                        'id' => 'activate',
                        'label' => __('Save & Activate'),
                        'data_attribute' => [
                            'mage-init' => [
                                'button' => ['event' => 'saveAndActivate', 'target' => '#edit_form'],
                                'glugoxIntegration' => ['gridUrl' => $this->getUrl('*/*/')],
                            ],
                        ],
                    ],
                ]
                    ]
            );
        }
    }


    /**
     * Get header text for edit page.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText() {
        if ($this->_isNewIntegration()) {
            return __('New Integration');
        } else {
            return __(
                    "Edit Integration '%1'", $this->escapeHtml(
                            $this->_registry->registry(IntegrationModel::CURRENT_INTEGRATION_KEY)[Main::DATA_NAME]
                    )
            );
        }
    }


    /**
     * {@inheritdoc}
     */
    public function getFormActionUrl() {
        return $this->getUrl('*/*/save');
    }


    /**
     * Determine whether we create new integration or editing an existing one.
     *
     * @return bool
     */
    protected function _isNewIntegration() {
        return !isset($this->_registry->registry(IntegrationModel::CURRENT_INTEGRATION_KEY)[Main::DATA_ID]);
    }


}
