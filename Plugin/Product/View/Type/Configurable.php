<?php

namespace Lof\ProductLabel\Plugin\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as TypeConfigurable;
use Magento\Framework\Json\Decoder;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Lof\ProductLabel\Helper\Config;
use Lof\ProductLabel\Block\Label;

/**
 * Class Configurable
 *
 * @package Lof\ProductLabel\Plugin\Product\View\Type
 */
class Configurable
{
    const LABEL_RELOAD = 'lofproductlabel/ajax/label';

    /**
     * @var Decoder
     */
    private $jsonDecoder;

    /**
     * @var EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Config
     */
    private $helper;

    public function __construct(
        Decoder $jsonDecoder,
        EncoderInterface $jsonEncoder,
        UrlInterface $urlBuilder,
        RequestInterface $request,
        Config $helper
    ) {
        $this->jsonDecoder = $jsonDecoder;
        $this->jsonEncoder = $jsonEncoder;
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->helper = $helper;
    }

    /**
     * @param TypeConfigurable $subject
     * @param $result
     * @return string
     */
    public function afterGetJsonConfig(
        TypeConfigurable $subject,
        $result
    ) {
        $result = $this->jsonDecoder->decode($result);

        $result['label_reload'] = $this->getReloadUrl();
        $result['label_category'] = $this->helper->getModuleConfig(Label::XML_DISPLAY_CATEGORY);
        $result['label_product'] = $this->helper->getModuleConfig(Label::XML_DISPLAY_PRODUCT);
        $result['original_product_id'] = $subject->getProduct()->getId();

        return $this->jsonEncoder->encode($result);
    }

    /**
     * @return string
     */
    private function getReloadUrl()
    {
        return $this->urlBuilder->getUrl(
            self::LABEL_RELOAD,
            [
                '_secure' => $this->request->isSecure()
            ]
        );
    }
}
