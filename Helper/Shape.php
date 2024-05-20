<?php

namespace Lof\ProductLabel\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;

/**
 * Class Shape
 *
 * @package Lof\ProductLabel\Helper
 */
class Shape extends AbstractHelper {
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var array
     */
    private $shapeTypes = [
        'circle'             => 'Circle',
        'transparent_circle' => 'Transparent Circle',
        'rquarter'           => 'Right Quarter',
        'lquarter'           => 'Left Quarter',
        'rbquarter'          => 'Right Bottom Quarter',
        'lbquarter'          => 'Left Bottom Quarter',
        'note'               => 'Note',
        'flag'               => 'Flag',
        'banner'             => 'Banner',
        'tag'                => 'Tag',
    ];

    /**
     * @var array
     */
    private $transparentShapes = [
        'transparent_circle',
        'transparent_rectangle',
    ];

    public function __construct(
        Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\View\Asset\Repository $assetRepo
    )
    {
        parent::__construct( $context );
        $this->messageManager = $messageManager;
        $this->filesystem     = $filesystem;
        $this->urlBuilder     = $context->getUrlBuilder();
        $this->ioFile         = $ioFile;
        $this->assetRepo      = $assetRepo;
    }

    /**
     * @return array
     */
    public function getShapes()
    {
        return $this->shapeTypes;
    }

    /**
     * @param $shape
     * @param $color
     * @return bool|string
     */
    public function generateNewLabel( $shape, $color )
    {
        $color    = str_replace( '#', '', $color );
        $fileName = $shape . '_' . $color . '.svg';
        $svg      = $this->getLabelFolder() . $fileName;

        if ( $this->ioFile->fileExists( $svg ) ) {
            return $fileName;
        } else {
            $svg = $this->getLabelFolder() . $shape . '.svg';
            if ( ! $this->ioFile->fileExists( $svg ) ) {
                $modulePath = $this->getModuleImagePath( $shape );

                $this->ioFile->cp( $modulePath, $svg );
            }

            if ( $this->ioFile->fileExists( $svg ) ) {
                $fileContents = $this->ioFile->read( $svg );
                if ( $color ) {
                    $fileContents = $this->changeColorImage(
                        $fileContents,
                        $color,
                        in_array( $shape, $this->transparentShapes ),
                        $svg
                    );
                }
                if ( $fileContents ) {
                    $newName = $this->getLabelFolder() . $fileName;
                    if ( $this->copyAndRenameImage( $fileContents, $newName ) ) {
                        return $fileName;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param $shape
     * @return |null
     */
    public function getModuleImagePath( $shape )
    {
        $fileId = 'Lof_ProductLabel::images/productlabel/' . $shape . '.svg';
        $params = [
            'area' => 'frontend',
        ];
        $asset  = $this->assetRepo->createAsset( $fileId, $params );
        try {
            return $asset->getSourceFile();
        } catch ( \Exception $e ) {
            return null;
        }
    }

    /**
     * @param $shape
     * @param $type
     * @param $checked
     * @return string
     */
    public function generateShape( $shape, $type, $checked )
    {
        $html = '<div class="lofproductlabel-shape">';
        $html .= '<input ' . $checked . ' type="radio" value="' . $shape . '" name="' .
                 $type . '" id="shape_' . $shape . $type . '">';
        $svg  = $this->getModuleLabelPath( $shape );
        $html .= '<label for="shape_' . $shape . $type . '">';
        $html .= '<img src="' . $svg . '" class="lofproductlabel-shape-image">';
        $html .= '</label>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @param        $fileContents
     * @param        $color
     * @param        $transparent
     * @param string $imageSvgFile
     *
     * @return bool|string
     */
    private function changeColorImage( $fileContents, $color, $transparent, $imageSvgFile )
    {
        $document                     = new \DOMDocument();
        $document->preserveWhiteSpace = false;
        if ( $document->loadXML( $fileContents ) ) {
            if ( $transparent ) {
                $allTags = $document->getElementsByTagName( "g" );
                if ( $allTags->length == 0 ) {
                    $allTags = $document->getElementsByTagName( "path" );
                }
                if ( $item = $allTags->item( 0 ) ) {
                    $item->setAttribute( 'stroke', '#' . $color );
                    $fileContents = $document->saveXML( $document );
                }
            } else {
                $allTags = $document->getElementsByTagName( "path" );
                foreach ( $allTags as $tag ) {
                    $vectorColor = $tag->getAttribute( 'fill' );
                    if ( strtoupper( $vectorColor ) != '#FFFFFF' ) {
                        $tag->setAttribute( 'fill', '#' . $color );
                        $fileContents = $document->saveXML( $document );
                        break;
                    }
                }
            }
        } else {
            $this->messageManager->addErrorMessage(
                __( 'Failed to load SVG file %1 as XML.  It probably contains malformed data.', $imageSvgFile )
            );

            return false;
        }

        return $fileContents;
    }

    /**
     * @param $fileContents
     * @param $newName
     * @return bool
     */
    private function copyAndRenameImage( $fileContents, $newName )
    {
        try {
            $this->ioFile->write( $newName, $fileContents );

            return true;
        } catch ( \Exception $exc ) {
            $this->messageManager->addErrorMessage( $exc->getMessage() );

            return false;
        }
    }

    /**
     * @return string
     */
    private function getLabelFolder()
    {
        $path = $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            Config::LOF_PRODUCTLABEL_MEDIA_PATH
        );

        return $path;
    }

    /**
     * @return string
     */
    private function getModuleLabelFolder()
    {
        return $this->assetRepo->getUrl( 'Lof_ProductLabel/images/productlabel/' );
    }

    /**
     * @return string
     */
    private function getLabelPath()
    {
        $path = $this->urlBuilder->getBaseUrl( [ '_type' => UrlInterface::URL_TYPE_MEDIA ] );
        $path .= Config::LOF_PRODUCTLABEL_MEDIA_PATH;

        return $path;
    }

    /**
     * @return string
     */
    public function getModuleLabelPath( $image )
    {
        return $this->assetRepo->getUrl( 'Lof_ProductLabel/images/productlabel/' . $image . '.svg' );
    }
}
