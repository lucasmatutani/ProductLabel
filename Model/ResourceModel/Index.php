<?php

namespace Lof\ProductLabel\Model\ResourceModel;

use Lof\ProductLabel\Api\Data\LabelIndexInterface;
use \Lof\ProductLabel\Api\Data\LabelInterface as LabelInterface;

/**
 * Class Index
 *
 * @package Lof\ProductLabel\Model\ResourceModel
 */
class Index extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
	const PRODUCTLABEL_INDEX_TABLE = 'lof_productlabel_index';

	/**
	 * Model Initialization
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init( self::PRODUCTLABEL_INDEX_TABLE, 'index_id' );
	}

	/**
	 * @param int $productId
	 * @param int $storeId
	 * @return array
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getIdsFromIndex( $productId, $storeId )
	{
		$query = $this->getConnection()
		              ->select()
		              ->from( $this->getMainTable(), LabelIndexInterface::LABEL_ID )
		              ->distinct()
		              ->where( LabelIndexInterface::PRODUCT_ID . ' = ?', $productId )
		              ->where( LabelIndexInterface::STORE_ID . ' = ?', $storeId );

		return $this->getConnection()->fetchAll( $query );
	}

	/**
	 * @param $labelsIds
	 */
	public function cleanByLabelIds( $labelsIds )
	{
		$query = $this->getConnection()->deleteFromSelect(
			$this->getConnection()
			     ->select()
			     ->from( $this->getMainTable(), LabelIndexInterface::LABEL_ID )
			     ->where( LabelIndexInterface::LABEL_ID . ' IN (?)', $labelsIds ),
			$this->getMainTable()
		);

		$this->getConnection()->query( $query );
	}

	/**
	 * @param $productIds
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function cleanByProductIds( $productIds )
	{
		$query = $this->getConnection()->deleteFromSelect(
			$this->getConnection()
			     ->select()
			     ->from( $this->getMainTable(), LabelIndexInterface::LABEL_ID )
			     ->where( LabelIndexInterface::PRODUCT_ID . ' IN (?)', $productIds ),
			$this->getMainTable()
		);

		$this->getConnection()->query( $query );
	}

	/**
	 * @param $labelId
	 * @return int|void
	 */
	public function getCountLabelIndexes( $labelId )
	{
		return $this->getCountIndex( $labelId );
	}

	/**
	 * @param int|string $labelId
	 * @param bool       $guest
	 * @return int|void
	 */
	public function getCountIndex( $labelId )
	{
		$query = $this->getConnection()
		              ->select()
		              ->from( $this->getMainTable(), LabelIndexInterface::LABEL_ID )
		              ->where( LabelIndexInterface::LABEL_ID . ' IN (?)', $labelId );

		return count( $this->getConnection()->fetchAll( $query ) );
	}

	/**
	 * @return $this
	 */
	public function cleanAllIndex()
	{
		$this->getConnection()->delete( $this->getMainTable() );

		return $this;
	}

	/**
	 * @param array $data
	 * @return $this
	 */
	public function insertIndexData( array $data )
	{
		$this->getConnection()->insertOnDuplicate( $this->getMainTable(), $data );

		return $this;
	}
}
