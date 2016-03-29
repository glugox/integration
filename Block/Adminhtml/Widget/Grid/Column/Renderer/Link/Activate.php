<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Block\Adminhtml\Widget\Grid\Column\Renderer\Link;

use Magento\Framework\DataObject;
use Glugox\Integration\Block\Adminhtml\Widget\Grid\Column\Renderer\Link;
use Glugox\Integration\Model\Integration;

class Activate extends Link
{
    /**
     * {@inheritDoc}
     */
    public function getCaption()
    {
        return $this->_row->getStatus() == Integration::STATUS_INACTIVE ? __('Activate') : __('Deactivate');
    }

    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getUrl(DataObject $row)
    {
        return 'javascript:void(0);';
    }

    /**
     * {@inheritDoc}
     */
    protected function _getAttributes()
    {
        return array_merge(parent::_getAttributes(), ['onclick' => 'glugox.integration.popup.show(this);']);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getDataAttributes()
    {
        return [
            'row-id' => $this->_row->getId(),
            'row-dialog' => 'permissions',
            'row-is-deactivate' => $this->_row->getStatus() == Integration::STATUS_INACTIVE ? '0' : '1'
        ];
    }
}
