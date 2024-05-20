<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class Edit
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class Edit extends \Lof\ProductLabel\Controller\Adminhtml\Label {

	/**
	 * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	public function execute()
	{
		$id    = $this->getRequest()->getParam( 'id' );
		$model = $this->labelRepository->getModelLabel();

		if ( $id ) {
			$model = $this->labelRepository->getById( $id );
			if ( ! $model->getId() ) {
				$this->messageManager->addErrorMessage( __( 'This label does not exist.' ) );
				$this->_redirect( '*/*' );

				return;
			}
		}

		// Set session.
		$data = $this->_session->getPageData( true );
		if ( ! empty( $data ) ) {
			$model->addData( $data );
		}
		$this->coreRegistry->register( 'current_lofproductlabel_label', $model );
		$this->_initAction();

		// Set title and breadcrumbs
		$title      = $id ? __( 'Edit Product Label' ) : __( 'New Product Label' );
		$resultPage = $this->resultPageFactory->create();
		$resultPage->addBreadcrumb( __( 'Catalog' ), __( 'Catalog' ) )
		           ->addBreadcrumb( __( 'Manage Product Labels' ), __( 'Manage Product Labels' ) );
		if ( ! empty( $title ) ) {
			$resultPage->addBreadcrumb( $title, $title );
		}

		$resultPage->getConfig()->getTitle()->prepend( __( 'Product Labels' ) );
		$resultPage->getConfig()->getTitle()->prepend( $id ? $model->getName() : __( 'New Product Label' ) );

		$this->_view->renderLayout();
	}

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_edit' ) && $this->config->isEnabled();
    }
}
