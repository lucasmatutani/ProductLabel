<?php

namespace Lof\ProductLabel\Plugin\Catalog\Product;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Plugin\Catalog\Product
 */
class Label {
    /**
     * @var \Lof\ProductLabel\Model\LabelViewer
     */
    private $helper;

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Label constructor.
     *
     * @param \Lof\ProductLabel\Model\LabelViewer     $helper
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Registry             $registry
     */
    public function __construct(
        \Lof\ProductLabel\Model\LabelViewer $helper,
        \Lof\ProductLabel\Helper\Config $config,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry
    )
    {
        $this->helper   = $helper;
        $this->config   = $config;
        $this->request  = $request;
        $this->registry = $registry;
    }

    /**
     * @param \Magento\Catalog\Block\Product\Image $subject
     * @param                                      $result
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterToHtml(
        \Magento\Catalog\Block\Product\Image $subject,
        $result
    )
    {
        if ( ! $this->config->isEnabled() ) {
            return $result;
        }

        $product    = $subject->getProduct();
        $moduleName = $this->request->getModuleName();
        if ( $product && $moduleName !== 'checkout' ) {
            $result .= $this->helper->renderProductLabel( $product, 'category' );
            $this->registry->register( 'lofproductlabel_category_observer', true, true );
        }

        return $result;
    }
}
