<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Reindex
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class Reindex extends \Magento\Backend\App\Action {
    /**
     * @var \Lof\ProductLabel\Model\Indexer\LabelIndexer
     */
    private $labelIndexer;

    /**
     * @var \Lof\ProductLabel\Helper\Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Reindex constructor.
     *
     * @param \Magento\Backend\App\Action\Context          $context
     * @param \Lof\ProductLabel\Model\Indexer\LabelIndexer $labelIndexer
     * @param \Lof\ProductLabel\Helper\Config              $config
     * @param \Psr\Log\LoggerInterface                     $logger
     */
    public function __construct(
        Action\Context $context,
        \Lof\ProductLabel\Model\Indexer\LabelIndexer $labelIndexer,
        \Lof\ProductLabel\Helper\Config $config,
        \Psr\Log\LoggerInterface $logger
    )
    {
        parent::__construct( $context );
        $this->labelIndexer = $labelIndexer;
        $this->config       = $config;
        $this->logger       = $logger;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam( 'id' );
        if ( $id ) {
            try {
                $this->labelIndexer->executeByLabelId( $id );
                $this->messageManager->addSuccessMessage( __( 'The label has been re-indexed.' ) );
                $this->_redirect( '*/*/edit', [ 'id' => $id ] );

                return;
            } catch ( LocalizedException $e ) {
                $this->messageManager->addErrorMessage( $e->getMessage() );
            } catch ( \Exception $e ) {
                $this->messageManager->addErrorMessage(
                    __( 'Something went wrong. Please review the log and try again.' )
                );
                $this->logger->critical( $e );
                $this->_redirect( '*/*/edit', [ 'id' => $id ] );

                return;
            }
        }
        $this->messageManager->addErrorMessage( __( 'Something went wrong.' ) );
        $this->_redirect( '*/*/' );
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_save' ) && $this->config->isEnabled();
    }
}
