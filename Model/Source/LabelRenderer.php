<?php

namespace Lof\ProductLabel\Model\Source;

use Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory;

/**
 * Class LabelRenderer
 *
 * @package Lof\ProductLabel\Model\Source
 */
class LabelRenderer implements \Magento\Framework\Option\ArrayInterface, \Magento\Config\Model\Config\CommentInterface {
    /**
     * @var CollectionFactory
     */
    private $labelCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Backend\Block\Context
     */
    private $context;

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    private $helper;

    public function __construct(
        CollectionFactory $labelCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Lof\ProductLabel\Helper\Config $helper,
        \Magento\Backend\Block\Context $context
    )
    {
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->storeManager           = $storeManager;
        $this->context                = $context;
        $this->helper                 = $helper;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $storeId    = $this->storeManager->getStore()->getId();
        $collection = $this->labelCollectionFactory->create()
                                                   ->addFieldToFilter( 'stores', [ 'like' => "%$storeId%" ] )
                                                   ->addFieldToFilter( 'stock_status', 1 )
                                                   ->setOrder( 'priority', 'asc' );
        $labels     = [ [ 'value' => 0, 'label' => __( '-- Please select --' ) ] ];
        foreach ( $collection as $label ) {
            $labels[] = [
                'value' => $label->getId(),
                'label' => $label->getName(),
            ];
        }

        return $labels;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $labels = [];
        foreach ( $this->toOptionArray() as $label ) {
            $labels[ $label['value'] ] = $label['label'];
        }

        return $labels;
    }

    /**
     * @param string $currentValue
     * @return string
     */
    public function getCommentText( $currentValue = '' )
    {
        return __( 'Just show this specific Out of Stock label and hide all other active labels if the product is Out of Stock.' );
    }
}
