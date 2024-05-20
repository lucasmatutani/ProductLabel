<?php

namespace Lof\ProductLabel\Api\Data;

interface LabelIndexInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const INDEX_ID = 'index_id';
    const LABEL_ID = 'label_id';
    const PRODUCT_ID = 'product_id';
    const STORE_ID = 'store_id';
    /**#@-*/

    /**
     * @return int
     */
    public function getIndexId();

    /**
     * @param int $indexId
     *
     * @return \Lof\ProductLabel\Api\Data\LabelIndexInterface
     */
    public function setIndexId($indexId);

    /**
     * @return int|null
     */
    public function getLabelId();

    /**
     * @param int|null $labelId
     *
     * @return \Lof\ProductLabel\Api\Data\LabelIndexInterface
     */
    public function setLabelId($labelId);

    /**
     * @return int|null
     */
    public function getProductId();

    /**
     * @param int|null $productId
     *
     * @return \Lof\ProductLabel\Api\Data\LabelIndexInterface
     */
    public function setProductId($productId);

    /**
     * @return int|null
     */
    public function getStoreId();

    /**
     * @param int|null $storeId
     *
     * @return \Lof\ProductLabel\Api\Data\LabelIndexInterface
     */
    public function setStoreId($storeId);
}
