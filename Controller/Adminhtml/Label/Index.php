<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class Index
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class Index extends \Lof\ProductLabel\Controller\Adminhtml\Label
{
	/**
	 * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
	 */
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Lof_ProductLabel::label');
		$resultPage->getConfig()->getTitle()->prepend(__('Product Labels'));
		$resultPage->addBreadcrumb(__('Lof'), __('Lof'));
		$resultPage->addBreadcrumb(__('Product Labels'), __('Product Labels'));
		return $resultPage;
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
