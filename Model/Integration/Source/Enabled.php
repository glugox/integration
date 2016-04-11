<?php
/**
 * This file is part of Glugox.
 *
 * (c) Glugox <glugox@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Glugox\Integration\Model\Integration\Source;

/**
 * Integration Enabled options.
 */
class Enabled implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve status options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => \Glugox\Integration\Model\Integration::STATUS_DISABLED, 'label' => __('Disabled')],
            ['value' => \Glugox\Integration\Model\Integration::STATUS_ENABLED, 'label' => __('Enabled')]
        ];
    }
}
