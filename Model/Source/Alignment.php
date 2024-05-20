<?php

namespace Lof\ProductLabel\Model\Source;

/**
 * Class Alignment
 *
 * @package Lof\ProductLabel\Model\Source
 */
class Alignment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('Vertical')
            ],
            [
                'value' => 1,
                'label' => __('Horizontal')
            ]
        ];
    }
}
