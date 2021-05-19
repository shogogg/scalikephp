<?php
declare(strict_types=1);
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */

namespace Test\ScalikePHP;

interface MockInterface extends \Mockery\MockInterface
{
    /**
     * {@inheritdoc}
     *
     * @param array ...$methods
     */
    public function shouldReceive(...$methods);

    /**
     * {@inheritdoc}
     *
     * @param array ...$methods
     */
    public function shouldNotReceive(...$methods);
}
