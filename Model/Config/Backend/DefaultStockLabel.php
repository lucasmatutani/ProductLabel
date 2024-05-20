<?php

namespace Lof\ProductLabel\Model\Config\Backend;

/**
 * Class DefaultStockLabel
 *
 * @package Lof\ProductLabel\Model\Config\Backend
 */
class DefaultStockLabel extends \Magento\Framework\App\Config\Value {
	/**
	 * @return \Lof\ProductLabel\Model\Config\Backend\DefaultStockLabel
	 */
	public function beforeSave()
	{
		if ( $this->isValueChanged() ) {
			$id = $this->getOldValue();
			$this->getData( 'config' )->changeStatus( $id, 0 );
		}
		$id = $this->getValue();
		$this->getData( 'config' )->changeStatus( $id, 1 );

		return parent::beforeSave();
	}
}
