<?php

namespace Lof\ProductLabel\Plugin\Catalog\Product;

/**
 * Class ImageBuilder
 *
 * @package Lof\ProductLabel\Plugin\Catalog\Product
 */
class ImageBuilder {
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * ImageBuilder constructor.
     *
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
    }

    /**
     * @param \Magento\Catalog\Block\Product\ImageBuilder $subject
     * @param                                             $result
     * @return mixed
     */
    public function afterCreate(
        \Magento\Catalog\Block\Product\ImageBuilder $subject,
        $result
    )
    {
        if ( is_object( $result ) ) {
            $result->setProduct( $this->registry->registry( 'lofproductlabel_current_product' ) );
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Block\Product\ImageBuilder $subject
     * @param \Closure                                    $proceed
     * @param \Magento\Catalog\Model\Product              $product
     * @return mixed
     */
    public function aroundSetProduct(
        \Magento\Catalog\Block\Product\ImageBuilder $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
    )
    {
        $result = $proceed( $product );
        $this->registry->unregister( 'lofproductlabel_current_product' );
        $this->registry->register( 'lofproductlabel_current_product', $product );

        return $result;
    }

    public function beforeSetImageId(
        \Magento\Catalog\Block\Product\ImageBuilder $subject,
        $imageId
    )
    {
        if ( $imageId == 'cart_page_product_thumbnail' ) {
            $this->registry->unregister( 'lofproductlabel_current_product' );
        }

        return [ $imageId ];
    }
}
