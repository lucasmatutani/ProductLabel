<?php

namespace Lof\ProductLabel\Plugin\Catalog\Product;

/**
 * Class ListProduct
 *
 * @package Lof\ProductLabel\Plugin\Catalog\Product
 */
class ListProduct {
	/**
	 * @var \Lof\ProductLabel\Model\LabelViewer
	 */
	private $helper;

    /**
     * @var
     */
	protected $config;

	/**
	 * @var \Magento\Framework\Registry
	 */
	private $registry;

    /**
     * ListProduct constructor.
     *
     * @param \Lof\ProductLabel\Model\LabelViewer $helper
     * @param \Magento\Framework\Registry         $registry
     */
	public function __construct(
		\Lof\ProductLabel\Model\LabelViewer $helper,
        \Lof\ProductLabel\Helper\Config $config,
		\Magento\Framework\Registry $registry
	)
	{
		$this->helper   = $helper;
		$this->config   = $config;
		$this->registry = $registry;
	}

	/**
	 * @param  $subject
	 * @param  $result
	 * @return string
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function afterToHtml(
		$subject,
		$result
	)
	{
	    if ( ! $this->config->isEnabled() ) {
	        return $result;
        }

        if ( ! $this->registry->registry( 'lofproductlabel_category_observer' ) && ! $subject->getIsLoflabelObserved() ) {
            $products = $subject->getLoadedProductCollection();
            if ( ! $products ) {
                $products = $subject->getProductCollection();
            }
            if ( $products ) {
                foreach ( $products as $product ) {
                    $result .= $this->helper->renderProductLabel( $product, 'category', true );
                }
                $subject->setIsLoflabelObserved( true );
            }
        }

        return $result;
	}
}
