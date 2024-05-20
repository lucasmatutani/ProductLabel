<?php

namespace Lof\ProductLabel\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Lof\ProductLabel\Helper
 */
class Config extends AbstractHelper {
    const LOF_PRODUCTLABEL_MEDIA_PATH = 'lofproductlabel/label/';
    const LOF_PRODUCTLABEL_CONFIG_PATH = 'lofproductlabel/';
    const MAX_LABELS = 999;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory
     */
    private $labelCollectionFactory;

    /**
     * @var \Lof\ProductLabel\Model\Repository\LabelRepository
     */
    private $labelsRepository;

    /**
     * @var \Lof\ProductLabel\Helper\Shape
     */
    protected $shapeHelper;

    /**
     * Image constructor.
     *
     * @param Context                                    $context
     * @param \Magento\Framework\Filesystem\Io\File      $ioFile
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem              $filesystem
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Lof\ProductLabel\Model\ResourceModel\Label\CollectionFactory $labelCollectionFactory,
        \Lof\ProductLabel\Model\Repository\LabelRepository $labelsRepository,
        \Lof\ProductLabel\Helper\Shape $shapeHelper
    )
    {
        parent::__construct( $context );
        $this->filesystem             = $filesystem;
        $this->ioFile                 = $ioFile;
        $this->storeManager           = $storeManager;
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->labelsRepository       = $labelsRepository;
        $this->shapeHelper            = $shapeHelper;
    }

    /**
     * Is module enabled?
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getModuleConfig( 'general/enabled' ) ? true : false;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getModuleConfig( $path )
    {
        return $this->scopeConfig->getValue(
            self::LOF_PRODUCTLABEL_CONFIG_PATH . $path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return image URL
     *
     * @param string $name
     * @return string
     */
    public function getImageUrl( $name )
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            self::LOF_PRODUCTLABEL_MEDIA_PATH
        );

        if ( $name != '' && $this->ioFile->fileExists( $path . $name ) ) {
            $path = $this->storeManager->getStore()->getBaseUrl( UrlInterface::URL_TYPE_MEDIA );

            return $path . self::LOF_PRODUCTLABEL_MEDIA_PATH . $name;
        }

        return '';
    }

    /**
     * @param string $name
     * @return string
     */
    public function getImagePath( $name )
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            self::LOF_PRODUCTLABEL_MEDIA_PATH
        );

        if ( $this->ioFile->fileExists( $path . $name ) && $name != "" ) {
            return $path . $name;
        }

        return '';
    }

    /**
     * Return shape URL.
     *
     * @param string $name
     * @return string
     */
    public function getShapeUrl( $name )
    {
        return $this->shapeHelper->getModuleLabelPath( $name );
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isLabelExist( $id )
    {
        $label = $this->labelCollectionFactory->create()
                                              ->addFieldToFilter( 'stores', $this->storeManager->getStore()->getId() )
                                              ->addFieldToFilter( 'entity_id', $id );

        return (bool) $label->getSize();
    }

    /**
     * @param int $id
     * @param int $status
     */
    public function changeStatus( $id, $status )
    {
        if ( $this->isLabelExist( $id ) ) {
            $label = $this->labelsRepository->getById( $id );
            $label->setStatus( $status );
            $label->save();
        }
    }

    /**
     * @return int
     */
    public function getMaxLabels()
    {
        $maxLabels = $this->getModuleConfig( 'display/max_labels' );
        if ( $maxLabels === null ) {
            $maxLabels = self::MAX_LABELS;
        }

        return (int) $maxLabels;
    }
}
