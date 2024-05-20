<?php

namespace Lof\ProductLabel\Model\Config\Backend;

/**
 * Class StockStatus
 *
 * @package Lof\ProductLabel\Model\Config\Backend
 */
class StockStatus extends \Magento\Framework\App\Config\Value {
	/**
	 * @return \Lof\ProductLabel\Model\Config\Backend\StockStatus
	 */
	public function beforeSave()
	{
		if ( $this->isValueChanged() ) {
			$id     = $this->getData( 'config' )->getModuleConfig( 'stock_status/default_label' );
			$status = $this->getValue();
			$this->getData( 'config' )->changeStatus( $id, $status );
		}

		return parent::beforeSave();
	}
}
