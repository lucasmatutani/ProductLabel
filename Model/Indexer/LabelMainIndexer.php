<?php

namespace Lof\ProductLabel\Model\Indexer;

/**
 * Class LabelMainIndexer
 *
 * @package Lof\ProductLabel\Model\Indexer
 */
class LabelMainIndexer extends LabelIndexer {
	/**
	 * Execute materialization on ids entities
	 *
	 * @param int[] $ids
	 */
	public function execute( $ids )
	{
		$this->executeByLabelIds( $ids );
	}
}
