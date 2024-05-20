<?php

namespace Lof\ProductLabel\Model\ResourceModel\Label;

/**
 * Class Collection
 *
 * @package Lof\ProductLabel\Model\ResourceModel\Label
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'lof_productlabel_label_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'label_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init( 'Lof\ProductLabel\Model\Label', 'Lof\ProductLabel\Model\ResourceModel\Label' );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    /**
     * @return \Lof\ProductLabel\Model\ResourceModel\Label\Collection
     */
    public function addActiveFilter()
    {
        $this->addFieldToFilter('status', 1);

        return $this;
    }
}
