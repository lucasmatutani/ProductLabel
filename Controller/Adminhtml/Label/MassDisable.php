<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

/**
 * Class MassDelete
 */
class MassDisable extends MassActionAbstract {
    /**
     * {@inheritdoc}
     */
    protected function itemAction( $label )
    {
        $label->setStatus( 0 );
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
