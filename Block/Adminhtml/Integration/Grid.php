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

use Magento\Backend\Block\Widget\Grid as BackendGrid;

/**
 * @codeCoverageIgnore
 */
class Grid extends BackendGrid {

    /**
     * Disable javascript callback on row clicking.
     *
     * @return string
     */
    public function getRowClickCallback() {
        return '';
    }


    /**
     * Disable javascript callback on row init.
     *
     * @return string
     */
    public function getRowInitCallback() {
        return '';
    }


}
