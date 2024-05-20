<?php

namespace Lof\ProductLabel\Plugin\Catalog\Indexer\Product;

use Magento\Catalog\Model\Indexer\Product\Price as PriceIndexer;
use Lof\ProductLabel\Model\Indexer\LabelIndexer;
use Magento\Catalog\Cron\RefreshSpecialPrices;

/**
 * Class Price
 *
 * @package Lof\ProductLabel\Plugin\Catalog\Indexer\Product
 */
class Price {
    /**
     * @var array
     */
    private $ids;

    /**
     * @var bool
     */
    private $needReindex = false;

    /**
     * @var LabelIndexer
     */
    private $labelIndexer;

    /**
     * Price constructor.
     *
     * @param \Lof\ProductLabel\Model\Indexer\LabelIndexer $labelIndexer
     */
    public function __construct(
        LabelIndexer $labelIndexer
    )
    {
        $this->labelIndexer = $labelIndexer;
    }

    /**
     * @param PriceIndexer $subject
     * @param array        $ids
     *
     * @return array
     */
    public function beforeExecuteList( PriceIndexer $subject, $ids )
    {
        $this->ids = $ids;

        return [ $ids ];
    }

    /**
     * @param PriceIndexer $subject
     */
    public function afterExecuteList(
        PriceIndexer $subject
    )
    {
        if ( $this->needReindex ) {
            $this->labelIndexer->execute( $this->ids );
        }
        $this->ids = [];
    }

    /**
     * @param       $subject
     * @param array $ids
     *
     * @return array
     */
    public function beforeExecute( $subject, $ids = [] )
    {
        if ( $subject instanceof RefreshSpecialPrices ) {
            $this->needReindex = true;
        }

        return [ $ids ];
    }
}
