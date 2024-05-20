<?php

namespace Lof\ProductLabel\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 *
 * @package Lof\ProductLabel\Model\Source
 */
class Status implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('Inactive')
            ],
            [
                'value' => 1,
                'label' => __('Active')
            ]
        ];
    }
}
