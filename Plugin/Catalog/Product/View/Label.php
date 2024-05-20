<?php

namespace Lof\ProductLabel\Plugin\Catalog\Product\View;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Plugin\Catalog\Product\View
 */
class Label {
    /**
     * @var array
     */
    private $allowedNames = [
        'product.info.media.magiczoomplus',
        'product.info.media.image',
        'product.info.media.magicthumb.younify',
    ];

    /**
     * @var \Lof\ProductLabel\Model\LabelViewer
     */
    private $helper;

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    protected $config;

    /**
     * Label constructor.
     *
     * @param \Lof\ProductLabel\Model\LabelViewer $helper
     */
    public function __construct(
        \Lof\ProductLabel\Model\LabelViewer $helper,
        \Lof\ProductLabel\Helper\Config $config
    )
    {
        $this->helper = $helper;
        $this->config = $config;
    }

    /**
     * @param $subject
     * @param $result
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

        $product = $subject->getProduct();
        $name    = $subject->getNameInLayout();

        if ( $product
             && in_array( $name, $this->getAllowedNames() )
             && ! $subject->getLoflabelObserved()
        ) {
            $subject->setLoflabelObserved( true );
            $result .= $this->helper->renderProductLabel( $product, 'product' );
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedNames()
    {
        return $this->allowedNames;
    }
}
