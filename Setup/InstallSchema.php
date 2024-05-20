<?php

namespace Lof\ProductLabel\Setup;

use Magento\Framework\DB\Ddl\Table as TableDdl;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface {

    const PRODUCTLABEL_TABLE = 'lof_productlabel_label';
    const PRODUCTLABEL_INDEX_TABLE = 'lof_productlabel_index';

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $installer = $setup;
        $installer->startSetup();

        if ( ! $installer->tableExists( self::PRODUCTLABEL_TABLE ) ) {
            $productlabelTable = $installer->getConnection()->newTable(
                $installer->getTable( self::PRODUCTLABEL_TABLE )
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Entity ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Name'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Status'
            )->addColumn(
                'priority',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Priority'
            )->addColumn(
                'exclusively',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Exclusively'
            )->addColumn(
                'use_for_parent',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Use for parent'
            )->addColumn(
                'stores',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => '', 'nullable' => false],
                'Stores'
            )->addColumn(
                'product_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product type'
            )->addColumn(
                'product_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product image'
            )->addColumn(
                'product_shape',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product shape'
            )->addColumn(
                'product_label_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product label color'
            )->addColumn(
                'product_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product position'
            )->addColumn(
                'product_image_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product image size'
            )->addColumn(
                'product_label_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                ],
                'Product label text'
            )->addColumn(
                'product_text_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product text color'
            )->addColumn(
                'product_text_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product text size'
            )->addColumn(
                'product_custom_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Product custom css'
            )->addColumn(
                'same_as_product',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'unsigned' => true,
                ],
                'Same As Product'
            )->addColumn(
                'cat_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category type'
            )->addColumn(
                'cat_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category image'
            )->addColumn(
                'cat_shape',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category shape'
            )->addColumn(
                'cat_label_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category label color'
            )->addColumn(
                'cat_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category position'
            )->addColumn(
                'cat_image_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category image size'
            )->addColumn(
                'cat_label_text',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                ],
                'Category label text'
            )->addColumn(
                'cat_text_color',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category text color'
            )->addColumn(
                'cat_text_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category text size'
            )->addColumn(
                'cat_custom_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => true,
                ],
                'Category custom css'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [ 'nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT ],
                'Created At'
            )->addColumn(
                'is_new',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['default' => 0, 'nullable' => false],
                'Is new'
            )
             ->addColumn(
                 'is_sale',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Is sale'
             )
             ->addColumn(
                 'special_price_only',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Special Price Only'
             )
             ->addColumn(
                 'stock_less',
                 \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                 null,
                 ['default' => null, 'nullable' => true],
                 'Stock less'
             )
            ->addColumn(
                'stock_higher',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'default' => null, 'nullable' => true ],
                'Stock higher'
            )
             ->addColumn(
                 'stock_more',
                 \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Stock more'
             )
             ->addColumn(
                 'stock_status',
                 \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Stock status'
             )
             ->addColumn(
                 'from_date',
                 \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                 null,
                 ['nullable' => true],
                 'From Date'
             )
             ->addColumn(
                 'to_date',
                 \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                 null,
                 ['nullable' => true],
                 'To Date'
             )
             ->addColumn(
                 'date_range_enabled',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Date range enabled'
             )
             ->addColumn(
                 'from_price',
                 \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                 '10,4',
                 ['default' =>  '0.0000', 'nullable' => false],
                 'From price'
             )
             ->addColumn(
                 'to_price',
                 \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                 '10,4',
                 ['default' =>  '0.0000', 'nullable' => false],
                 'To price'
             )
             ->addColumn(
                 'by_price',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'By price'
             )
             ->addColumn(
                 'price_range_enabled',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Price range enabled'
             )
             ->addColumn(
                 'customer_group_ids',
                 \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                 null,
                 ['nullable' => false],
                 'Customer groups'
             )
             ->addColumn(
                 'cond_serialize',
                 \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                 null,
                 ['nullable' => false],
                 'Conditions'
             )
             ->addColumn(
                 'customer_group_enabled',
                 \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                 ['default' => 0, 'nullable' => false],
                 'Customer group enabled'
             )->setComment( 'Lof Product Label Table' );

            $installer->getConnection()->createTable( $productlabelTable );
        }

        if ( ! $installer->tableExists( self::PRODUCTLABEL_INDEX_TABLE ) ) {
            $productlabelIndexTable = $installer->getConnection()->newTable(
                $installer->getTable( self::PRODUCTLABEL_INDEX_TABLE )
            )->addColumn(
                'index_id',
                TableDdl::TYPE_BIGINT,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true,
                ],
                'Index Id'
            )->addColumn(
                'label_id',
                TableDdl::TYPE_INTEGER,
                null,
                [ 'unsigned' => true ],
                'Label Id'
            )->addColumn(
                'product_id',
                TableDdl::TYPE_INTEGER,
                null,
                [ 'unsigned' => true ],
                'Product Id'
            )->addColumn(
                'store_id',
                TableDdl::TYPE_INTEGER,
                null,
                [ 'unsigned' => true ],
                'Store Id'
            )->addIndex(
                $installer->getIdxName(
                    self::PRODUCTLABEL_INDEX_TABLE,
                    [
                        'label_id',
                        'product_id',
                        'store_id',
                    ],
                    true
                ),
                [
                    'label_id',
                    'product_id',
                    'store_id',
                ],
                [ 'type' => 'unique' ]
            )->addIndex(
                $installer->getIdxName( self::PRODUCTLABEL_INDEX_TABLE, [ 'label_id' ] ),
                [ 'label_id' ]
            )->addIndex(
                $installer->getIdxName( self::PRODUCTLABEL_INDEX_TABLE, [ 'product_id' ] ),
                [ 'product_id' ]
            )->addIndex(
                $installer->getIdxName( self::PRODUCTLABEL_INDEX_TABLE, [ 'store_id' ] ),
                [ 'store_id' ]
            )->addForeignKey(
                $installer->getFkName(
                    self::PRODUCTLABEL_INDEX_TABLE,
                    'label_id',
                    self::PRODUCTLABEL_TABLE,
                    'entity_id'
                ),
                'label_id',
                $installer->getTable( self::PRODUCTLABEL_TABLE ),
                'entity_id',
                TableDdl::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName(
                    self::PRODUCTLABEL_INDEX_TABLE,
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable( 'catalog_product_entity' ),
                'entity_id',
                TableDdl::ACTION_CASCADE
            )->setComment(
                'Lof Product Label Index'
            );

            $installer->getConnection()->createTable( $productlabelIndexTable );
        }

        $installer->endSetup();
    }
}
