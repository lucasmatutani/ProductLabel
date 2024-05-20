<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class MassDelete
 */
class MassDelete extends MassActionAbstract {
    /**
     * {@inheritdoc}
     */
    protected function getSuccessMessage( $collectionSize = 0 )
    {
        return __( 'A total of %1 record(s) have been deleted.', $collectionSize );
    }

    /**
     * {@inheritdoc}
     */
    protected function itemAction( $label )
    {
        $label->delete();
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_delete' ) && $this->config->isEnabled();
    }
}
