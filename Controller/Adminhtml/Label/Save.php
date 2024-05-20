<?php

namespace Lof\ProductLabel\Controller\Adminhtml\Label;

use Lof\ProductLabel\Api\Data\LabelInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\MediaStorage\Model\File\Uploader;

/**
 * Class Save
 *
 * @package Lof\ProductLabel\Controller\Adminhtml\Label
 */
class Save extends \Lof\ProductLabel\Controller\Adminhtml\Label {
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $data    = $this->getRequest()->getPostValue();
        $labelId = intval( $this->getRequest()->getParam( 'entity_id' ) );
        if ( $data ) {
            try {
                /** @var LabelInterface $model */
                $model = $this->getLabel( $labelId );

                $data = $this->validateLabelData( $data );
                $data = $this->processStyle( $data );
                $model->setData( $data );

                $this->_session->setPageData( $model->getData() );
                $this->processImage( $model );
                $this->processShape( $model );
                $model->save();

                $this->messageManager->addSuccessMessage( __( 'The label is saved.' ) );
                if ( $this->labelIndexer->isIndexerScheduled() ) {
                    $this->labelIndexer->invalidateIndex();
                } else {
                    $this->labelIndexer->executeByLabelId( $model->getId() );
                }

                $this->_session->setPageData( false );

                if ( $this->getRequest()->getParam( 'back' ) ) {
                    $this->_redirect( '*/*/edit', [ 'id' => $model->getId() ] );

                    return;
                }

                $this->_redirect( '*/*/' );

                return;
            } catch ( LocalizedException $e ) {
                $this->messageManager->addErrorMessage( $e->getMessage() );
                $this->_session->setPageData( $data );
            } catch ( \Exception $e ) {
                $this->messageManager->addErrorMessage(
                    __( 'Something went wrong while saving the item data. Please review the error log.' )
                );
                $this->logger->critical( $e );
                $this->_session->setPageData( $data );
            }
            if ( ! empty( $labelId ) ) {
                $this->_redirect( '*/*/edit', [ 'id' => $labelId ] );
            } else {
                $this->_redirect( '*/*/new' );
            }

            return;
        }
        $this->_redirect( '*/*/' );
    }

    /**
     * @param $labelId
     * @return LabelInterface|bool
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getLabel( $labelId )
    {
        if ( $labelId ) {
            $model = $this->labelRepository->getById( $labelId );
            if ( $labelId != $model->getLabelId() ) {
                throw new LocalizedException( __( 'The wrong label is specified.' ) );
            }
        } else {
            $model = $this->labelRepository->getModelLabel();
        }

        return $model;
    }

    /**
     * @param $data
     * @return mixed
     * @throws LocalizedException
     */
    private function validateLabelData( $data )
    {
        if ( ! empty( $data['product_label_text'] ) ) {
            $data['product_label_text'] = $this->escaper->escapeHtml( $data['product_label_text'] );
        }
        if ( ! empty( $data['cat_label_text'] ) ) {
            $data['cat_label_text'] = $this->escaper->escapeHtml( $data['cat_label_text'] );
        }
        if ( ! empty( $data['stock_higher'] )
             && ! empty( $data['stock_less'] )
             && $data['stock_higher'] > $data['stock_less']
        ) {
            throw new LocalizedException(
                __( 'Please set field value \'Display if stock is higher than\' less than'
                    . ' field value \'Display if stock is lower than\'' )
            );
        }

        if ( isset( $data['customer_group_ids'] ) ) {
            $data['customer_group_ids'] = $this->serializer->serialize( $data['customer_group_ids'] );
        }

        /* If only one store exists*/
        if ( isset( $data['stores'] ) && ! $data['stores'] ) {
            $data['stores'] = 1;
        }
        if ( is_array( $data['stores'] ) ) {
            $data['stores'] = implode( ',', $data['stores'] );
        }

        if ( ! empty( $data['from_date'] ) ) {
            $tmpDate = explode( '/', $data['from_date'] );
            if ( count( $tmpDate ) > 1 ) {
                $tmpSwap           = $tmpDate[1];
                $tmpDate[1]        = $tmpDate[0];
                $tmpDate[0]        = $tmpSwap;
                $data['from_date'] = implode( '/', $tmpDate );
            }

        }

        if ( ! empty( $data['to_date'] ) ) {
            $tmpDate = explode( '/', $data['to_date'] );
            if ( count( $tmpDate ) > 1 ) {
                $tmpSwap         = $tmpDate[1];
                $tmpDate[1]      = $tmpDate[0];
                $tmpDate[0]      = $tmpSwap;
                $data['to_date'] = implode( '/', $tmpDate );
            }
        }

        if ( isset( $data['rule'] ) && isset( $data['rule']['conditions'] ) ) {
            $data['conditions'] = $data['rule']['conditions'];

            unset( $data['rule'] );

            $rule = $this->ruleFactory->create();
            $rule->loadPost( $data );

            $data['cond_serialize'] = $this->serializer->serialize( $rule->getConditions()->asArray() );
            unset( $data['conditions'] );
        }

        if ( ! empty( $data['to_time'] ) ) {
            $data['to_date'] = $data['to_date'] . ' ' . $data['to_time'];
        }

        if ( ! empty( $data['from_time'] ) ) {
            $data['from_date'] = $data['from_date'] . ' ' . $data['from_time'];
        }
        if ( ! isset( $data['stock_less'] ) || $data['stock_less'] === '' ) {
            $data['stock_less'] = null;
        }
        if ( ! isset( $data['stock_higher'] ) || $data['stock_higher'] === '' ) {
            $data['stock_higher'] = null;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function processStyle( $data )
    {
        $catStyles = $data['cat_custom_css'];
        if ( array_key_exists( 'cat_text_size', $data ) && $data['cat_text_size'] ) {
            $size = 'font-size: ' . $data['cat_text_size'] . ';';
            if ( strpos( $catStyles, 'font-size' ) !== false ) {
                $catStyles = preg_replace( "@font-size(.*?);@s", $size, $catStyles );
            } else {
                $catStyles .= $size;
            }
        }

        if ( array_key_exists( 'cat_text_color', $data ) && $data['cat_text_color'] ) {
            $color = ';color: ' . $data['cat_text_color'] . ';';
            if ( preg_match( '@(^|[^-])(color.*?);@s', $catStyles ) ) {
                $catStyles = preg_replace( "@(^|[^-])(color.*?);@s", $color, $catStyles );
            } else {
                $catStyles .= $color;
            }
        }

        $catStyles              = str_replace( ";;", ";", $catStyles );
        $data['cat_custom_css'] = $catStyles;

        $prodStyles = $data['product_custom_css'];
        if ( array_key_exists( 'product_text_size', $data ) && $data['product_text_size'] ) {
            $size = 'font-size: ' . $data['product_text_size'] . ';';
            if ( strpos( $prodStyles, 'font-size' ) !== false ) {
                $prodStyles = preg_replace( "@font-size(.*?);@s", $size, $prodStyles );
            } else {
                $prodStyles .= $size;
            }
        }

        if ( array_key_exists( 'product_text_color', $data ) && $data['product_text_color'] ) {
            $color = ';color: ' . $data['product_text_color'] . ';';
            if ( preg_match( '@(^|[^-])(color.*?);@s', $prodStyles ) ) {
                $prodStyles = preg_replace( "@(^|[^-])(color.*?);@s", $color, $prodStyles );
            } else {
                $prodStyles .= $color;
            }
        }

        $prodStyles                 = str_replace( ";;", ";", $prodStyles );
        $data['product_custom_css'] = $prodStyles;

        return $data;
    }

    /**
     * @param $model
     *
     * @return bool
     */
    private function processImage( $model )
    {
        // Process images
        $data = $this->getRequest()->getPost();
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'lofproductlabel/label/'
        );

        $this->ioFile->checkAndCreateFolder( $path );

        $imagesTypes = [ 'product', 'cat' ];
        foreach ( $imagesTypes as $type ) {
            $labelType = $type . '_type';
            if ( $data[$labelType]  !== 'image' ) {
                continue;
            }

            $field = $type . '_image';

            if ( $field ) {
                $files    = $this->getRequest()->getFiles();
                $isRemove = isset( $data[ 'remove_' . $field ] );
                $hasNew   = ! empty( $files[ $field ]['name'] );

                try {
                    // remove the old file
                    if ( $isRemove || $hasNew ) {
                        $oldName = isset( $data[ 'old_' . $field ] ) ? $data[ 'old_' . $field ] : '';
                        if ( $oldName ) {
                            $oldName = Uploader::getCorrectFileName( $oldName );
                            $this->ioFile->rm( $path . $oldName );
                            $model->setData( $field, '' );
                        }
                    }

                    // Upload a new if any
                    if ( ! $isRemove && $hasNew ) {
                        // Find the first available name
                        $newName = preg_replace( '/[^a-zA-Z0-9_\-\.]/', '', $files[ $field ]['name'] );
                        $newName = Uploader::getCorrectFileName( $newName );
                        if ( substr( $newName, 0, 1 ) == '.' ) {
                            $newName = 'label' . $newName;
                        }

                        $i = 0;
                        while ( $this->ioFile->fileExists( $path . $newName ) ) {
                            $newName = ( ++$i ) . $newName;
                        }

                        /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                        $uploader = $this->fileUploaderFactory->create( [ 'fileId' => $field ] );
                        $uploader->setAllowedExtensions( [ 'jpg', 'jpeg', 'gif', 'png', 'svg' ] );
                        $uploader->setAllowRenameFiles( true );
                        $uploader->save( $path, $newName );

                        $model->setData( $field, $newName );
                    }
                } catch ( \Exception $e ) {
                    if ( $e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY ) {
                        $this->messageManager->addErrorMessage( $e->getMessage() );
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param $model
     *
     * @return bool
     */
    private function processShape( $model )
    {
        // Process Shape
        $data = $this->getRequest()->getPost();
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'lofproductlabel/label/'
        );

        $this->ioFile->checkAndCreateFolder( $path );

        $imagesTypes = [ 'product', 'cat' ];
        foreach ( $imagesTypes as $type ) {
            $labelType = $type . '_type';
            if ( $data[$labelType]  !== 'shape' ) {
                continue;
            }

            $field = $type . '_shape';
            $imageField = $type . '_image';

            if ( $field ) {
                if ( isset( $data[ $field ] ) && $data[ $field ] ) {
                    $shape    = $data[ $field ];
                    $color    = $type . '_label_color';
                    $color    = $data[ $color ];
                    $fileName = $this->shapeHelper->generateNewLabel( $shape, $color );
                    $model->setData( $imageField, $fileName );
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed( 'Lof_ProductLabel::label_save' ) && $this->config->isEnabled();
    }
}
