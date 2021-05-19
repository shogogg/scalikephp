<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Base TestCase.
 *
 * @internal
 * @coversNothing
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Create a spy.
     *
     * @return \Mockery\MockInterface|MockInterface
     */
    public static function spy(): Mockery\MockInterface
    {
        return Mockery::spy();
    }
}
