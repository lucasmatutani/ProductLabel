<?php

namespace Lof\ProductLabel\Model\Indexer;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Class LabelIndexer
 *
 * @package Lof\ProductLabel\Model\Indexer
 */
class LabelIndexer implements IndexerActionInterface, MviewActionInterface, IdentityInterface {
    /**
     * Const Indexer ID
     */
    const INDEXER_ID = 'lof_productlabel_label';

    /**
     * @var IndexBuilder
     */
    private $indexBuilder;

    /**
     * Application Event Dispatcher
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    private $cacheManager;

    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * LabelIndexer constructor.
     *
     * @param IndexBuilder                               $indexBuilder
     * @param \Magento\Framework\Event\ManagerInterface  $eventManager
     * @param \Magento\Framework\App\CacheInterface      $cacheManager
     * @param \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry
     */
    public function __construct(
        IndexBuilder $indexBuilder,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\CacheInterface $cacheManager,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry
    )
    {
        $this->indexBuilder    = $indexBuilder;
        $this->eventManager    = $eventManager;
        $this->cacheManager    = $cacheManager;
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     */
    public function execute( $ids )
    {
        $this->executeList( $ids );
    }

    /**
     * Execute full indexation
     *
     * @throws LocalizedException
     */
    public function executeFull()
    {
        $this->indexBuilder->reindexFull();
        $this->eventManager->dispatch( 'clean_cache_by_tags', [ 'object' => $this ] );
        $this->cacheManager->clean( $this->getIdentities() );
    }

    /**
     * Get affected cache tags
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getIdentities()
    {
        return [
            \Lof\ProductLabel\Model\AbstractLabel::CACHE_TAG,
        ];
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param array $ids
     */
    public function executeList( array $ids )
    {
        $this->doExecuteList( $ids );
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @return void
     * @throws LocalizedException
     */
    public function executeRow( $id )
    {
        if ( $this->getIndexer()->isScheduled() ) {
            return;
        }
        if ( ! $id ) {
            throw new LocalizedException(
                __( 'We can\'t rebuild the index for an undefined product.' )
            );
        }

        $this->doExecuteRow( $id );
    }

    /**
     * Execute partial indexation by ID list. Template method
     *
     * @param $ids
     * @throws LocalizedException
     */
    private function doExecuteList( $ids )
    {
        $this->indexBuilder->reindexByProductId( $ids );
    }

    /**
     * Execute partial indexation by ID. Template method
     *
     * @param int $id
     * @return void
     * @throws LocalizedException
     */
    private function doExecuteRow( $id )
    {
        $this->indexBuilder->reindexByProductId( $id );
    }

    /**
     * @throws LocalizedException
     */
    public function doExecuteFull()
    {
        $this->executeFull();
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     */
    public function executeByLabelId( $id )
    {
        $this->indexBuilder->reindexByLabelId( $id );
    }

    /**
     * Execute partial indexation by ID
     *
     * @param array $ids
     */
    public function executeByLabelIds( $ids )
    {
        $this->indexBuilder->reindexByLabelIds( $ids );
    }

    /**
     * Invalidate Index
     */
    public function invalidateIndex()
    {
        $labelIndexer = $this->getIndexer();
        if ( ! $labelIndexer->isScheduled() ) {
            $labelIndexer->invalidate();
        }
    }

    /**
     * Check if indexer is on scheduled
     *
     * @return bool
     */
    public function isIndexerScheduled()
    {
        return $this->getIndexer()->isScheduled();
    }

    /**
     * @return \Magento\Framework\Indexer\IndexerInterface
     */
    private function getIndexer()
    {
        return $this->indexerRegistry->get( self::INDEXER_ID );
    }
}
