<?php

namespace Lof\ProductLabel\Api\Data;

interface LabelInterface {

    const LABEL_ID = 'entity_id';
    const NAME = 'name';
    const STATUS = 'status';
    const PRIORITY = 'priority';
    const EXCLUSIVELY = 'exclusively';
    const USE_FOR_PARENT = 'use_for_parent';

    const PRODUCT_TYPE = 'product_type';
    const PRODUCT_IMG = 'product_image';
    const PRODUCT_SHAPE = 'product_shape';
    const PRODUCT_LABEL_TEXT = 'product_label_text';
    const PRODUCT_LABEL_COLOR = 'product_label_color';
    const PRODUCT_TEXT_COLOR = 'product_text_color';
    const PRODUCT_TEXT_SIZE = 'product_text_size';
    const PRODUCT_IMAGE_SIZE = 'product_image_size';
    const PRODUCT_POSITION = 'product_position';
    const PRODUCT_CUSTOM_CSS = 'product_custom_css';

    const SAME_AS_PRODUCT = 'same_as_product';
    const CAT_TYPE = 'cat_type';
    const CAT_IMG = 'cat_image';
    const CAT_SHAPE = 'cat_shape';
    const CAT_LABEL_TEXT = 'cat_label_text';
    const CAT_LABEL_COLOR = 'cat_label_color';
    const CAT_TEXT_COLOR = 'cat_text_color';
    const CAT_TEXT_SIZE = 'cat_text_size';
    const CAT_IMAGE_SIZE = 'cat_image_size';
    const CAT_POSITION = 'cat_position';
    const CAT_CUSTOM_CSS = 'cat_custom_css';

    const STORES = 'stores';
    const IS_NEW = 'is_new';
    const IS_SALE = 'is_sale';
    const SPECIAL_PRICE_ONLY = 'special_price_only';
    const STOCK_LESS = 'stock_less';
    const STOCK_MORE = 'stock_more';
    const STOCK_STATUS = 'stock_status';
    const FROM_DATE = 'from_date';
    const TO_DATE = 'to_date';
    const DATE_RANGE_ENABLED = 'date_range_enabled';
    const FROM_PRICE = 'from_price';
    const TO_PRICE = 'to_price';
    const BY_PRICE = 'by_price';
    const PRICE_RANGE_ENABLED = 'price_range_enabled';
    const CUSTOMER_GROUP_IDS = 'customer_group_ids';
    const COND_SERIALIZE = 'cond_serialize';
    const CUSTOMER_GROUP_ENABLED = 'customer_group_enabled';
    const PRODUCT_STOCK_ENABLED = 'product_stock_enabled';
    const STOCK_HIGHER = 'stock_higher';

    /**
     * @return int
     */
    public function getLabelId();

    /**
     * @param int $labelId
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setLabelId( $labelId );

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setPriority( $priority );

    /**
     * @return int
     */
    public function getExclusively();

    /**
     * @param int $exclusively
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setExclusively( $exclusively );

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setName( $name );

    /**
     * @return string
     */
    public function getStores();

    /**
     * @param string $stores
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStores( $stores );

    /**
     * @return string
     */
    public function getProdType();

    /**
     * @param string $prodType
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdType( $prodType );

    /**
     * @return string
     */
    public function getProdShape();

    /**
     * @param string $prodShape
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdShape( $prodShape );

    /**
     * @return string
     */
    public function getProdTxt();

    /**
     * @param string $prodTxt
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdTxt( $prodTxt );

    /**
     * @return string
     */
    public function getProdImg();

    /**
     * @param string $prodImg
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdImg( $prodImg );

    /**
     * @return string
     */
    public function getProdImageSize();

    /**
     * @param string $prodImageSize
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdImageSize( $prodImageSize );

    /**
     * @return int
     */
    public function getProdPos();

    /**
     * @param int $prodPos
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdPos( $prodPos );

    /**
     * @return string
     */
    public function getProdStyle();

    /**
     * @param string $prodStyle
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdStyle( $prodStyle );

    /**
     * @return string
     */
    public function getProdTextStyle();

    /**
     * @param string $prodTextStyle
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProdTextStyle( $prodTextStyle );

    /**
     * @return string
     */
    public function getCatType();

    /**
     * @param string $catType
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatType( $catType );

    /**
     * @return string
     */
    public function getCatTxt();

    /**
     * @param string $catTxt
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatTxt( $catTxt );

    /**
     * @return string
     */
    public function getCatImg();

    /**
     * @param string $catImg
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatImg( $catImg );

    /**
     * @return int
     */
    public function getCatPos();

    /**
     * @param int $catPos
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatPos( $catPos );

    /**
     * @return string
     */
    public function getCatStyle();

    /**
     * @param string $catStyle
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatStyle( $catStyle );

    /**
     * @return string
     */
    public function getCatImageSize();

    /**
     * @param string $catImageSize
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatImageSize( $catImageSize );

    /**
     * @return string
     */
    public function getCatTextStyle();

    /**
     * @param string $catTextStyle
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCatTextStyle( $catTextStyle );

    /**
     * @return int
     */
    public function getIsNew();

    /**
     * @param int $isNew
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setIsNew( $isNew );

    /**
     * @return int
     */
    public function getIsSale();

    /**
     * @param int $isSale
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setIsSale( $isSale );

    /**
     * @return int
     */
    public function getSpecialPriceOnly();

    /**
     * @param int $specialPriceOnly
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setSpecialPriceOnly( $specialPriceOnly );

    /**
     * @return int|null
     */
    public function getStockLess();

    /**
     * @param int|null $stockLess
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStockLess( $stockLess );

    /**
     * @return int
     */
    public function getStockMore();

    /**
     * @param int $stockMore
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStockMore( $stockMore );

    /**
     * @return int
     */
    public function getStockStatus();

    /**
     * @param int $stockStatus
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStockStatus( $stockStatus );

    /**
     * @return string|null
     */
    public function getFromDate();

    /**
     * @param string|null $fromDate
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setFromDate( $fromDate );

    /**
     * @return string|null
     */
    public function getToDate();

    /**
     * @param string|null $toDate
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setToDate( $toDate );

    /**
     * @return int
     */
    public function getDateRangeEnabled();

    /**
     * @param int $dateRangeEnabled
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setDateRangeEnabled( $dateRangeEnabled );

    /**
     * @return float
     */
    public function getFromPrice();

    /**
     * @param float $fromPrice
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setFromPrice( $fromPrice );

    /**
     * @return float
     */
    public function getToPrice();

    /**
     * @param float $toPrice
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setToPrice( $toPrice );

    /**
     * @return int
     */
    public function getByPrice();

    /**
     * @param int $byPrice
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setByPrice( $byPrice );

    /**
     * @return int
     */
    public function getPriceRangeEnabled();

    /**
     * @param int $priceRangeEnabled
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setPriceRangeEnabled( $priceRangeEnabled );

    /**
     * @return string
     */
    public function getCustomerGroupIds();

    /**
     * @param string $customerGroupIds
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCustomerGroupIds( $customerGroupIds );

    /**
     * @return string
     */
    public function getCondSerialize();

    /**
     * @param string $condSerialize
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCondSerialize( $condSerialize );

    /**
     * @return int
     */
    public function getCustomerGroupEnabled();

    /**
     * @param int $customerGroupEnabled
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setCustomerGroupEnabled( $customerGroupEnabled );

    /**
     * @return int
     */
    public function getUseForParent();

    /**
     * @param int $useForParent
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setUseForParent( $useForParent );

    /**
     * @return int|null
     */
    public function getStatus();

    /**
     * @param int|null $status
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStatus( $status );

    /**
     * @return int|null
     */
    public function getProductStockEnabled();

    /**
     * @param int|null $productStockEnabled
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setProductStockEnabled( $productStockEnabled );

    /**
     * @return int|null
     */
    public function getStockHigher();

    /**
     * @param int|null $stockHigher
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function setStockHigher( $stockHigher );
}
