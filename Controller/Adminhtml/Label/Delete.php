<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class Delete
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class Delete extends \Lof\ProductLabel\Controller\Adminhtml\Label {
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam( 'id' );
        if ( $id ) {
            try {
                $model = $this->labelRepository->getById( $id );
                $model->delete();
                $this->labelIndexer->invalidateIndex();
                $this->messageManager->addSuccessMessage( __( 'The label is deleted.' ) );
                $this->_redirect( '*/*/' );

                return;
            } catch ( \Magento\Framework\Exception\LocalizedException $e ) {
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
        $this->messageManager->addErrorMessage( __( 'We can\'t find a item to delete.' ) );
        $this->_redirect( '*/*/' );
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_delete' ) && $this->config->isEnabled();
    }
}
