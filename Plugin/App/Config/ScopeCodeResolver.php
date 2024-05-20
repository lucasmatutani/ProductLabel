<?php

namespace Lof\ProductLabel\Plugin\App\Config;

/**
 * Class ScopeCodeResolver
 *
 * @package Lof\ProductLabel\Plugin\App\Config
 */
class ScopeCodeResolver
{
    /**
     * @var bool
     */
    private $needClean = false;

    /**
     * @param \Magento\Framework\App\Config\ScopeCodeResolver $scopeCodeResolver
     * @param string $scopeType
     * @param string $scopeCode
     *
     * @return array
     */
    public function beforeResolve(
        $scopeCodeResolver,
        $scopeType,
        $scopeCode
    ) {
        if ($this->isNeedClean() && method_exists($scopeCodeResolver, 'clean')) {
            $scopeCodeResolver->clean();
        }

        return [$scopeType, $scopeCode];
    }

    /**
     * @param bool $needClean
     */
    public function setNeedClean($needClean)
    {
        $this->needClean = $needClean;
    }

    /**
     * @return bool
     */
    public function isNeedClean()
    {
        return $this->needClean;
    }
}
