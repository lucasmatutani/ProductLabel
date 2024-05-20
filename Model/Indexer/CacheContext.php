<?php

namespace Lof\ProductLabel\Model\Indexer;

/**
 * Class CacheContext
 *
 * @package Lof\ProductLabel\Model\Indexer
 */
class CacheContext extends \Magento\Framework\Indexer\CacheContext {
	/**
	 * Register entity Ids
	 *
	 * @param string $cacheTag
	 * @param array  $ids
	 * @return $this
	 */
	public function registerEntities( $cacheTag, $ids )
	{
		$this->entities[ $cacheTag ] = $ids;

		return $this;
	}
}
