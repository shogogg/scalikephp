<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP\Support;

/**
 * Support functions for Map.
 */
trait GeneralSupport
{

    /**
     * Get raw iterable.
     *
     * @return iterable
     */
    abstract protected function getRawIterable(): iterable;

}
