<?php
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
