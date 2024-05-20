<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit;

/**
 * Class Form
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label\Edit
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setId( 'lofproductlabel_label_form' );
		$this->setTitle( __( 'Label Information' ) );
	}

	/**
	 * Prepare form before rendering HTML
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create(
			[
				'data' => [
					'id'      => 'edit_form',
					'action'  => $this->getUrl( 'lofproductlabel/label/save' ),
					'method'  => 'post',
					'enctype' => 'multipart/form-data',
				],
			]
		);

		$form->setUseContainer( true );
		$this->setForm( $form );

		return parent::_prepareForm();
	}
}
