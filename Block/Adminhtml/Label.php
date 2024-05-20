<?php

namespace Lof\ProductLabel\Block\Adminhtml;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Block\Adminhtml
 */
class Label extends \Magento\Backend\Block\Widget\Grid\Container {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_controller     = 'label';
		$this->_headerText     = __( 'Product Labels' );
		$this->_addButtonLabel = __( 'Add New label' );
		parent::_construct();
	}
}
