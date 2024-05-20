<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab;

/**
 * Class Product
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab
 */
class Product extends AbstractImage {
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __( 'Product Detail Page' );
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __( 'Product Detail Page' );
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry( 'current_lofproductlabel_label' );

        /** @var \Magento\Framework\Data\Form $form */
        $form       = $this->_formFactory->create();
        $fldProduct = $form->addFieldset( 'product_page', [ 'legend' => __( 'Product Detail Page' ) ] );

        $fldProduct->addType( 'lof_color', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Color::class );
        $fldProduct->addType( 'lof_shape', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Shape::class );
        $fldProduct->addType( 'lof_image', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Image::class );

        $fldProduct->addField(
            'product_type',
            'radios',
            [
                'label'              => __( 'Label Type' ),
                'name'               => 'product_type',
                'values'             => [
                    [ 'value' => 'text_only', 'label' => __( 'Text Only' ) ],
                    [ 'value' => 'shape', 'label' => __( 'Shape' ) ],
                    [ 'value' => 'image', 'label' => __( 'Image' ) ],
                ],
                'index'              => 'text_only',
                'after_element_html' => $this->getJsHtml( 'product_type' ),
            ]
        );

        $fldProduct->addField(
            'product_shape',
            'lof_shape',
            [
                'label' => __( 'Shape' ),
                'name'  => 'product_shape',
            ]
        );

        $fldProduct->addField(
            'product_image',
            'lof_image',
            [
                'label' => __( 'Image' ),
                'name'  => 'product_image',
            ]
        );

        $fldProduct->addField(
            'product_label_color',
            'lof_color',
            [
                'label' => __( 'Label Color' ),
                'name'  => 'product_label_color',
            ]
        );

        $fldProduct->addField(
            'product_position',
            'select',
            [
                'label'              => __( 'Label Position' ),
                'name'               => 'product_position',
                'values'             => $model->getAvailablePositions(),
                'after_element_html' => $this->getPositionHtml( 'product_position' ),
            ]
        );

        $fldProduct->addField(
            'product_image_size',
            'text',
            [
                'label' => __( 'Label Size' ),
                'name'  => 'product_image_size',
                'note'  => __( 'Percent of the product image. Ex: 20.' ),
            ]
        );

        $fldProduct->addField(
            'product_label_text',
            'text',
            [
                'label' => __( 'Label Text' ),
                'name'  => 'product_label_text',
                'note'  => __( $this->getLabelTextNote() ),
            ]
        );

        $fldProduct->addField(
            'product_text_color',
            'lof_color',
            [
                'label' => __( 'Text Color' ),
                'name'  => 'product_text_color',
            ]
        );

        $fldProduct->addField(
            'product_text_size',
            'text',
            [
                'label' => __( 'Text Size' ),
                'name'  => 'product_text_size',
                'note'  => __( 'Text size. Ex: 14px;' ),
            ]
        );

        $fldProduct->addField(
            'product_custom_css',
            'textarea',
            [
                'label' => __( 'Advanced Settings/CSS' ),
                'name'  => 'product_custom_css',
                'note'  => __(
                    'Customize label by CSS. <a href="%1" target="_blank">Documentation</a>.' .
                    '<br> Ex.: font-style: italic; text-align: center;',
                    'https://www.w3schools.com/cssref/default.asp'
                ),
            ]
        );

        if ( ! $model->getId() ) {
            $model->setData( 'product_type', 'text_only' );
            $model->setData( 'product_image_size', '20' );
        }

        $data = $model->getData();
        $data = $this->_restoreSizeColor( $data );
        $form->setValues( $data );
        $this->setForm( $form );

        return parent::_prepareForm();
    }
}
