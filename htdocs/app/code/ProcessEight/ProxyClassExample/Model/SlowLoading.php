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
 * Class SlowLoading
 *
 * A class designed to be deliberately slow loading in order to demonstrate Proxy classes
 *
 * @package ProcessEight\ProxyClassExample\Model
 */
class SlowLoading
{
    /**
     * SlowLoading constructor.
     */
    public function __construct()
    {
        // Do something resource-intensive (or detrimental to performance) upon instantiation of the object
        // For demonstration purposes. This could be an API call, a complex DB query, etc
        sleep(10);
    }

    /**
     * Return a trivial value
     *
     * @return string
     */
    public function getSlowValue() : string
    {
        return 'Slow loader';
    }
}
