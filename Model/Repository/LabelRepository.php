<?php

namespace Lof\ProductLabel\Model\Repository;

use Lof\ProductLabel\Api\Data\LabelInterface;
use Lof\ProductLabel\Api\LabelRepositoryInterface;
use Lof\ProductLabel\Model\LabelFactory;
use Lof\ProductLabel\Model\ResourceModel\Label as LabelResource;
use Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory;
use Lof\ProductLabel\Model\ResourceModel\Label\Collection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;

/**
 * Class LabelRepository
 *
 * @package Lof\ProductLabel\Model\Repository
 */
class LabelRepository implements LabelRepositoryInterface {
    /**
     * @var BookmarkSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var LabelFactory
     */
    private $labelFactory;

    /**
     * @var LabelResource
     */
    private $labelResource;

    /**
     * Model data storage
     *
     * @var array
     */
    private $labels;

    /**
     * @var CollectionFactory
     */
    private $labelCollectionFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var File
     */
    private $ioFile;

    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        LabelFactory $labelFactory,
        LabelResource $labelResource,
        CollectionFactory $labelCollectionFactory,
        Filesystem $filesystem,
        File $ioFile
    )
    {
        $this->searchResultsFactory   = $searchResultsFactory;
        $this->labelFactory           = $labelFactory;
        $this->labelResource          = $labelResource;
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->filesystem             = $filesystem;
        $this->ioFile                 = $ioFile;
    }

    /**
     * @inheritdoc
     */
    public function save( LabelInterface $label )
    {
        try {
            if ( $label->getId() ) {
                $label = $this->getById( $label->getId() )->addData( $label->getData() );
            }
            $this->labelResource->save( $label );
            unset( $this->labels[ $label->getId() ] );
        } catch ( \Exception $e ) {
            if ( $label->getId() ) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save labels with ID %1. Error: %2',
                        [ $label->getId(), $e->getMessage() ]
                    )
                );
            }
            throw new CouldNotSaveException( __( 'Unable to save new labels. Error: %1', $e->getMessage() ) );
        }

        return $label;
    }

    /**
     * @inheritdoc
     */
    public function getById( $id )
    {
        if ( ! isset( $this->labels[ $id ] ) ) {
            /** @var \Lof\ProductLabel\Model\Label $label */
            $label = $this->labelFactory->create();
            $this->labelResource->load( $label, $id );
            if ( ! $label->getId() ) {
                throw new NoSuchEntityException( __( 'Label with specified ID "%1" not found.', $id ) );
            }
            $this->labels[ $id ] = $label;
        }

        return $this->labels[ $id ];
    }

    /**
     * @inheritdoc
     */
    public function delete( LabelInterface $label )
    {
        try {
            $this->labelResource->delete( $label );
            unset( $this->labels[ $label->getId() ] );
        } catch ( \Exception $e ) {
            if ( $label->getId() ) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove labels with ID %1. Error: %2',
                        [ $label->getId(), $e->getMessage() ]
                    )
                );
            }
            throw new CouldNotDeleteException( __( 'Unable to remove labels. Error: %1', $e->getMessage() ) );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById( $id )
    {
        $labelModel = $this->getById( $id );
        $this->delete( $labelModel );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList( SearchCriteriaInterface $searchCriteria )
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria( $searchCriteria );

        /** @var \Lof\ProductLabel\Model\ResourceModel\Label\Collection $labelCollection */
        $labelCollection = $this->labelCollectionFactory->create();
        // Add filters from root filter group to the collection
        foreach ( $searchCriteria->getFilterGroups() as $group ) {
            $this->addFilterGroupToCollection( $group, $labelCollection );
        }
        $searchResults->setTotalCount( $labelCollection->getSize() );
        $sortOrders = $searchCriteria->getSortOrders();
        if ( $sortOrders ) {
            $this->addOrderToCollection( $sortOrders, $labelCollection );
        }
        $labelCollection->setCurPage( $searchCriteria->getCurrentPage() );
        $labelCollection->setPageSize( $searchCriteria->getPageSize() );
        $labels = [];
        /** @var LabelInterface $label */
        foreach ( $labelCollection->getItems() as $label ) {
            $labels[] = $this->getById( $label->getId() );
        }
        $searchResults->setItems( $labels );

        return $searchResults;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection  $labelCollection
     *
     * @return void
     */
    private function addFilterGroupToCollection( FilterGroup $filterGroup, Collection $labelCollection )
    {
        foreach ( $filterGroup->getFilters() as $filter ) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $labelCollection->addFieldToFilter( $filter->getField(), [ $condition => $filter->getValue() ] );
        }
    }

    /**
     * Helper function that adds a SortOrder to the collection.
     *
     * @param SortOrder[] $sortOrders
     * @param Collection  $labelCollection
     *
     * @return void
     */
    private function addOrderToCollection( $sortOrders, Collection $labelCollection )
    {
        /** @var SortOrder $sortOrder */
        foreach ( $sortOrders as $sortOrder ) {
            $field = $sortOrder->getField();
            $labelCollection->addOrder(
                $field,
                ( $sortOrder->getDirection() == SortOrder::SORT_DESC ) ? 'DESC' : 'ASC'
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        $labelCollection = $this->labelCollectionFactory->create();

        return $labelCollection->getItems();
    }

    /**
     * @return LabelInterface
     */
    public function getModelLabel()
    {
        return $this->labelFactory->create();
    }
}
