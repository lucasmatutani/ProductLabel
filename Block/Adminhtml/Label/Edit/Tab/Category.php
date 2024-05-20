<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab;

/**
 * Class Category
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab
 */
class Category extends AbstractImage {
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __( 'Category Page (Listing)' );
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __( 'Category Page (Listing)' );
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry( 'current_lofproductlabel_label' );

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fldCat = $form->addFieldset( 'category_page', [ 'legend' => __( 'Category Page (Listing)' ) ] );

        $fldCat->addType( 'lof_color', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Color::class );
        $fldCat->addType( 'lof_shape', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Shape::class );
        $fldCat->addType( 'lof_image', \Lof\ProductLabel\Block\Adminhtml\Data\Form\Element\Image::class );

        $fldCat->addField(
            'cat_type',
            'radios',
            [
                'label'              => __( 'Label Type' ),
                'name'               => 'cat_type',
                'values'             => [
                    [ 'value' => 'text_only', 'label' => __( 'Text Only' ) ],
                    [ 'value' => 'shape', 'label' => __( 'Shape' ) ],
                    [ 'value' => 'image', 'label' => __( 'Image' ) ],
                ],
                'after_element_html' => $this->getJsHtml( 'cat_type' ),
            ]
        );

        $fldCat->addField(
            'cat_shape',
            'lof_shape',
            [
                'label' => __( 'Shape' ),
                'name'  => 'cat_shape',
            ]
        );

        $fldCat->addField(
            'cat_image',
            'lof_image',
            [
                'label' => __( 'Image' ),
                'name'  => 'cat_image',
            ]
        );

        $fldCat->addField(
            'cat_label_color',
            'lof_color',
            [
                'label' => __( 'Label Color' ),
                'name'  => 'cat_label_color',
            ]
        );

        $fldCat->addField(
            'cat_position',
            'select',
            [
                'label'              => __( 'Label Position' ),
                'name'               => 'cat_position',
                'values'             => $model->getAvailablePositions(),
                'after_element_html' => $this->getPositionHtml( 'cat_position' ),
            ]
        );

        $fldCat->addField(
            'cat_image_size',
            'text',
            [
                'label' => __( 'Label Size' ),
                'name'  => 'cat_image_size',
                'note'  => __( 'Percent of the product image. Ex: 20.' ),
            ]
        );

        $fldCat->addField(
            'cat_label_text',
            'text',
            [
                'label' => __( 'Label Text' ),
                'name'  => 'cat_label_text',
                'note'  => __( $this->getLabelTextNote() ),
            ]
        );

        $fldCat->addField(
            'cat_text_color',
            'lof_color',
            [
                'label' => __( 'Text Color' ),
                'name'  => 'cat_text_color',
            ]
        );

        $fldCat->addField(
            'cat_text_size',
            'text',
            [
                'label' => __( 'Text Size' ),
                'name'  => 'cat_text_size',
                'note'  => __( 'Text size. Ex: 14px;' ),
            ]
        );

        $fldCat->addField(
            'cat_custom_css',
            'textarea',
            [
                'label' => __( 'Advanced Settings/CSS' ),
                'name'  => 'cat_custom_css',
                'note'  => __(
                    'Customize label by CSS. <a href="%1" target="_blank">Documentation</a>.' .
                    '<br> Ex.: font-style: italic; text-align: center;',
                    'https://www.w3schools.com/cssref/default.asp'
                ),
            ]
        );

        if ( ! $model->getId() ) {
            $model->setData( 'cat_type', 'text_only' );
            $model->setData( 'cat_image_size', '20' );
        }

        $data = $model->getData();
        $data = $this->_restoreSizeColor( $data );
        $form->setValues( $data );
        $this->setForm( $form );

        return parent::_prepareForm();
    }
}
