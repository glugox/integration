<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Block\Adminhtml\Integration\Edit;

use Glugox\Integration\Block\Adminhtml\Integration\Edit\Tab\Main;
use Glugox\Integration\Controller\Adminhtml\Integration;
use Glugox\Integration\Model\Integration as IntegrationModel;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $integrationData = $this->_coreRegistry->registry(IntegrationModel::CURRENT_INTEGRATION_KEY);
        if (isset($integrationData[Main::DATA_ID])) {
            $form->addField(Main::DATA_ID, 'hidden', ['name' => 'id']);
            $form->setValues($integrationData);
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
