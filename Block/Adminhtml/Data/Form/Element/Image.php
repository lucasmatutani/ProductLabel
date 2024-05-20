<?php

namespace Lof\ProductLabel\Block\Adminhtml\Data\Form\Element;

use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\UrlInterface;

/**
 * Class Image
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Data\Form\Element
 */
class Image extends \Magento\Framework\Data\Form\Element\File {
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;

    /**
     * Image constructor.
     *
     * @param \Magento\Framework\Data\Form\Element\Factory           $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper                             $escaper
     * @param \Magento\Framework\UrlInterface                        $urlBuilder
     * @param \Magento\Framework\View\Asset\Repository               $assetRepo
     * @param array                                                  $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        $data = []
    )
    {
        parent::__construct( $factoryElement, $factoryCollection, $escaper, $data );
        $this->urlBuilder = $urlBuilder;
        $this->assetRepo  = $assetRepo;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        return $this->getImageHtml();
    }

    /**
     * @return string
     */
    private function getImageHtml()
    {
        $html = '<div id="lofproductlabel-image' . $this->getHtmlId() . '" class="additional">';

        $img    = $this->getValue();
        $imgUrl = $img ? $this->getMediaPath() . $img : $this->getPlaceHolderImageUrl();

        $html .= '<div class="lofproductlabel-image-preview">';
        $html .= '<label for="' . $this->getHtmlId() . '">';
        $html .= '<img id="image_preview' . $this->getHtmlId() .
                 '" src="' . $imgUrl . '" width="250" height="250" />';
        $html .= '</label>';
        $html .= '</div>';

        $html .= '<div class="lofproductlabel-image-upload-button-wrap">';
        if ( $img ) {
            $html .= '<div class="lofproductlabel-remove-image-wrap"><label class="lofproductlabel-remove-image">
                    <input
                        type="checkbox"
                        value="1"
                        name="remove_' . $this->getHtmlId() .
                     '"/> ' . __( 'Remove' ) . '</label></div>';
        }

        $html .= '<label for="' . $this->getHtmlId() . '" class="lofproductlabel-file-upload-button">' . __( 'Upload new image' ) . '</label>';
        $html .= '</div>';
        $html .= '<div class="lofproductlabel-image-upload-wrap">';
        $html .= '<div class="lofproductlabel-image-upload">';
        $html .= '<input
                        style="margin-bottom: 3px;"
                        class="lofproductlabel-image-upload"
                        id="' . $this->getHtmlId() . '"
                        name="' . $this->getName() . '"
                        value="' . $this->getEscapedValue() . '"
                        onchange="document.getElementById(\'image_preview' . $this->getHtmlId() . '\').src = window.URL.createObjectURL(this.files[0])"
                        ' . $this->serialize( $this->getHtmlAttributes() )
                 . '/>';
        $html .= '</div>';
        if ( $img ) {
            $html .= '<input type="hidden" value="' . $img . '" name="old_' . $this->getHtmlId() . '"/>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @return string
     */
    private function getMediaPath()
    {
        $path = $this->urlBuilder->getBaseUrl( [ '_type' => UrlInterface::URL_TYPE_MEDIA ] );
        $path .= 'lofproductlabel/label/';

        return $path;
    }

    /**
     * @return string
     */
    public function getPlaceHolderImageUrl()
    {
        return $this->assetRepo->getUrl( 'Lof_ProductLabel/images/upload.svg' );
    }
}
