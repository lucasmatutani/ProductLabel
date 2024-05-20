<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class NewAction
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class NewAction extends \Lof\ProductLabel\Controller\Adminhtml\Label
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->_forward('edit');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_new' ) && $this->config->isEnabled();
    }
}
