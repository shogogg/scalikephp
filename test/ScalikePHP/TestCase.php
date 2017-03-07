<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace Test\ScalikePHP;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Base TestCase.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * Create a spy.
     *
     * @return MockInterface|\Mockery\MockInterface
     */
    public static function spy()
    {
        return Mockery::spy();
    }

}
