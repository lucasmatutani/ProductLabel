<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class MassDelete
 */
class MassEnable extends MassActionAbstract {
    /**
     * {@inheritdoc}
     */
    protected function itemAction( $label )
    {
        $label->setStatus( 1 );
        $label->save();
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_save' ) && $this->config->isEnabled();
    }
}
