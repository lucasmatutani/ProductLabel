<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label;

/**
 * Class Edit
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container {
	/**
	 *
	 */
	protected function _construct()
	{
		$this->_objectId   = 'id';
		$this->_controller = 'adminhtml_label';
		$this->_blockGroup = 'Lof_ProductLabel';

		parent::_construct();

		$this->buttonList->add(
			'save_and_continue_edit',
			[
				'class'          => 'save',
				'label'          => __( 'Save and Continue Edit' ),
				'data_attribute' => [
					'mage-init' => [ 'button' => [ 'event' => 'saveAndContinueEdit', 'target' => '#edit_form' ] ],
				],
			],
			10
		);

		$this->addButton(
			'reindex_button',
			[
				'label'          => __( 'Re-index' ),
				'class'          => 'save',
				'on_click'       => 'setLocation(\'' . $this->getReindexUrl() . '\')',
				'data_attribute' => [
					'mage-init' => [
						'button' => [ 'event' => 'UpdateEdit', 'target' => '#edit_form' ],
					],
				],
			]
		);
	}

	/**
	 * @return string
	 */
	public function getReindexUrl()
	{
		return $this->getUrl(
			'lofproductlabel/label/reindex',
			[ '_current' => true, 'back' => null, 'product_tab' => $this->getRequest()->getParam( 'product_tab' ) ]
		);
	}
}
