<?php

namespace Lof\ProductLabel\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 *
 * @package Lof\ProductLabel\Ui\Component\Listing\Column
 */
class Actions extends Column {
    /**
     * Url path
     */
    const URL_PATH_EDIT = 'lofproductlabel/label/edit';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Actions constructor.
     *
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory           $uiComponentFactory
     * @param \Magento\Framework\UrlInterface                              $urlBuilder
     * @param array                                                        $components
     * @param array                                                        $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct( $context, $uiComponentFactory, $components, $data );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource( array $dataSource )
    {
        if ( isset( $dataSource['data']['items'] ) ) {
            foreach ( $dataSource['data']['items'] as &$item ) {
                if ( isset( $item['entity_id'] ) ) {
                    $item[ $this->getData( 'name' ) ] = [
                        'edit' => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['entity_id'],
                                ]
                            ),
                            'label' => __( 'Edit' ),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
