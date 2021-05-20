<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

interface MockInterface extends \Mockery\MockInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string ...$methods
     */
    public function shouldReceive(...$methods);

    /**
     * {@inheritdoc}
     *
     * @param string ...$methods
     */
    public function shouldNotReceive(...$methods);
}
