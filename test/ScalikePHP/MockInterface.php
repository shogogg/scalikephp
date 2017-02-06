<?php
namespace Test\ScalikePHP;

interface MockInterface extends \Mockery\MockInterface
{

    /**
     * @inheritdoc
     * @param array ...$methods
     */
    public function shouldReceive(... $methods);

    /**
     * @inheritdoc
     * @param array ...$methods
     */
    public function shouldNotReceive(... $methods);

}
