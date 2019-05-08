<?php
/**
 * ProcessEight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 ProcessEight
 * @author      ProcessEight
 *
 */

declare(strict_types=1);

namespace ProcessEight\ProxyClassExample\Model;

/**
 * Class FastLoadingWithProxy
 *
 * A class designed to demonstrate Proxy classes
 *
 * @package ProcessEight\ProxyClassExample\Model
 */
class FastLoadingWithProxy
{
    /**
     * @var SlowLoading
     */
    private $slowLoading;

    /**
     * FastLoadingWithProxy constructor.
     *
     * Inject our slow loading class
     *
     * @param SlowLoading $slowLoading
     */
    public function __construct(
        \ProcessEight\ProxyClassExample\Model\SlowLoading $slowLoading
    ) {
        $this->slowLoading = $slowLoading;
    }

    /**
     * Return a trivial value
     *
     * @return string
     */
    public function getFastValue() : string
    {
        return 'Fast loader with proxy';
    }

    /**
     * Return the value from the slow loading object
     *
     * @return string
     */
    public function getSlowValue() : string
    {
        return $this->slowLoading->getSlowValue();
    }
}
