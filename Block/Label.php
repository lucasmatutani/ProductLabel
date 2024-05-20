<?php

namespace Lof\ProductLabel\Block;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Block
 */
class Label extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface {
    const XML_DISPLAY_PRODUCT = 'display/product';
    const XML_DISPLAY_CATEGORY = 'display/category';

    protected $_template = 'Lof_ProductLabel::label.phtml';

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    private $helper;

    /**
     * @var \Lof\ProductLabel\Model\Label
     */
    private $label;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * Label constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Lof\ProductLabel\Helper\Config                  $helper
     * @param \Magento\Framework\Json\EncoderInterface         $jsonEncoder
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Lof\ProductLabel\Helper\Config $helper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    )
    {
        parent::__construct( $context, $data );
        $this->helper      = $helper;
        $this->jsonEncoder = $jsonEncoder;

        if ( $this->getLabel() ) {
            $this->addData( [
                'cache_lifetime' => 86400,
            ] );
        }
    }

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $label     = $this->getLabel();
        $productId = $label->getParentProduct() ? $label->getParentProduct()->getId() : $label->getProduct()->getId();

        return $this->jsonEncoder->encode(
            [
                'position'  => $label->getCssClass(),
                'size'      => $label->getValue( 'image_size' ),
                'path'      => $this->getContainerPath(),
                'mode'      => $label->getMode(),
                'move'      => (int) $label->getShouldMove(),
                'product'   => $productId,
                'label'     => (int) $label->getId(),
                'margin'    => $this->helper->getModuleConfig( 'display/margin_between' ),
                'alignment' => $this->helper->getModuleConfig( 'display/label_alignment' ),
            ]
        );
    }

    /**
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $productId = $this->getLabel()->getProduct() ? $this->getLabel()->getProduct()->getId() : null;

        return [
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->getLabel()->getId(),
            $this->getLabel()->getMode(),
            $productId,
        ];
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return $this->getLabel()->getIdentities();
    }

    /**
     * @param \Lof\ProductLabel\Model\Label $label
     *
     * @return $this
     */
    public function setLabel( \Lof\ProductLabel\Model\Label $label )
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return \Lof\ProductLabel\Model\Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get container path from module settings
     *
     * @return string
     */
    public function getContainerPath()
    {
        if ( $this->label->getMode() == 'cat' ) {
            $path = $this->helper->getModuleConfig( self::XML_DISPLAY_CATEGORY );
        } else {
            $path = $this->helper->getModuleConfig( self::XML_DISPLAY_PRODUCT );
        }

        return $path;
    }

    /**
     * Get image url with mode and site url
     *
     * @return string
     */
    public function getImageScr()
    {
        $type = $this->getLabelType();

        if ( $type == 'text_only' ) {
            return '';
        } else {
            $imageSrc = $this->label->getValue( 'image' );

            return $this->helper->getImageUrl( $imageSrc );
        }
    }

    /**
     * @return string
     */
    public function getLabelType()
    {
        return $this->label->getValue( 'type' );
    }
}
