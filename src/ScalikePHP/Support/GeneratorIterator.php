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
 * GeneratorIterator - Rewindable iterator wrapping generator.
 */
final class GeneratorIterator implements \IteratorAggregate
{

    /**
     * @var \Generator
     */
    private $generator;

    /**
     * @var mixed[]
     */
    private $cache = [];

    /**
     * GeneratorIterator constructor.
     *
     * @param \Generator $generator
     */
    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
        $this->generator->rewind();
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): \Iterator {
        foreach ($this->cache as $key => $value) {
            yield $key => $value;
        }
        while ($this->generator->valid()) {
            $key = $this->generator->key();
            $value = $this->generator->current();
            $this->cache[$key] = $value;
            yield $key => $value;
            $this->generator->next();
        }
    }

}
