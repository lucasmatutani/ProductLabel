<?php

namespace Lof\ProductLabel\Model\ResourceModel;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Model\ResourceModel
 */
class Label extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    /**
     * Message constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct( $context );
    }

    /**
     * Construct init.
     */
    protected function _construct()
    {
        $this->_init( 'lof_productlabel_label', 'entity_id' );
    }
}
