<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

use Lof\ProductLabel\Api\Data\LabelInterface;
use Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory;
use Lof\ProductLabel\Api\LabelRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class MassDelete
 */
abstract class MassActionAbstract extends \Magento\Backend\App\Action {
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Lof\ProductLabel\Model\Indexer\LabelIndexer
     */
    private $labelIndexer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LabelRepositoryInterface
     */
    protected $labelRepository;

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    protected $config;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * MassActionAbstract constructor.
     *
     * @param \Magento\Backend\App\Action\Context                           $context
     * @param \Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory $collectionFactory
     * @param \Lof\ProductLabel\Model\Indexer\LabelIndexer                  $labelIndexer
     * @param \Lof\ProductLabel\Api\LabelRepositoryInterface                $labelRepository
     * @param \Psr\Log\LoggerInterface                                      $logger
     * @param \Magento\Ui\Component\MassAction\Filter                       $filter
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        \Lof\ProductLabel\Model\Indexer\LabelIndexer $labelIndexer,
        LabelRepositoryInterface $labelRepository,
        \Lof\ProductLabel\Helper\Config $config,
        LoggerInterface $logger,
        Filter $filter
    )
    {
        parent::__construct( $context );
        $this->collectionFactory = $collectionFactory;
        $this->labelIndexer      = $labelIndexer;
        $this->labelRepository   = $labelRepository;
        $this->config            = $config;
        $this->logger            = $logger;
        $this->filter            = $filter;
    }

    /**
     * @param LabelInterface $label
     *
     * @return void
     */
    abstract protected function itemAction( $label );

    /**
     * Mass action execution
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider(); // compatibility with Mass Actions on Magento 2.1.0
        /** @var \Lof\ProductLabel\Model\ResourceModel\Label\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection = $this->filter->getCollection( $collection );

        $collectionSize = $collection->getSize();
        if ( $collectionSize ) {
            try {
                foreach ( $collection->getItems() as $reminder ) {
                    $this->itemAction( $reminder );
                }
                $this->invalidateIndex();

                $this->messageManager->addSuccessMessage( $this->getSuccessMessage( $collectionSize ) );
            } catch ( \Magento\Framework\Exception\LocalizedException $e ) {
                $this->messageManager->addErrorMessage( $e->getMessage() );
            } catch ( \Exception $e ) {
                $this->messageManager->addErrorMessage( $this->getErrorMessage() );
                $this->logger->critical( $e );
            }
        }

        return $this->resultRedirectFactory->create()->setUrl( $this->_redirect->getRefererUrl() );
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage()
    {
        return __( 'Something went wrong!' );
    }

    /**
     * @param int $collectionSize
     *
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage( $collectionSize = 0 )
    {
        if ( $collectionSize ) {
            return __( 'A total of %1 record(s) have been changed.', $collectionSize );
        }

        return __( 'No records have been changed.' );
    }

    /**
     * Invalid Index
     */
    protected function invalidateIndex()
    {
        $this->labelIndexer->invalidateIndex();
    }

    /**
     * @return \Lof\ProductLabel\Model\ResourceModel\Label\Collection
     */
    protected function getCollection()
    {
        $ids        = $this->getRequest()->getParam( 'label_ids' );
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter( 'label_id', $ids );

        return $collection;
    }

    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label' ) && $this->config->isEnabled();
    }
}
