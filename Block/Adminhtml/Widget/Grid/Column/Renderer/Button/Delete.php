<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Block\Adminhtml\Widget\Grid\Column\Renderer\Button;

use Glugox\Integration\Model\Integration;
use Magento\Framework\DataObject;
use Glugox\Integration\Block\Adminhtml\Widget\Grid\Column\Renderer\Button;

class Delete extends Button
{
    /**
     * Return 'onclick' action for the button (redirect to the integration edit page).
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    protected function _getOnclickAttribute(DataObject $row)
    {
        return sprintf(
            "this.setAttribute('data-url', '%s')",
            $this->getUrl('*/*/delete', ['id' => $row->getId()])
        );
    }
    
    /**
     * @param Object $row
     * @return mixed
     */
    protected function _getValue(DataObject $row)
    {
        return $this->_getTitleAttribute($row);
    }

    /**
     * Get title depending on whether element is disabled or not.
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    protected function _getTitleAttribute(DataObject $row)
    {
        return __('Remove');
    }

    /**
     * Determine whether current integration is disabled.
     *
     * @param \Magento\Framework\DataObject $row
     * @return bool
     */
    protected function _isDisabled(DataObject $row)
    {
        return (bool) $row->getData( Integration::STATUS );
    }
}
