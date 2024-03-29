<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

use Iterator;

/**
 * CachingIterator - Rewindable iterator wrapping generator.
 */
final class CachingIterator implements \IteratorAggregate
{
    private \Iterator $iterator;
    private array $cache = [];

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\Support\CachingIterator}.
     *
     * @param \Iterator $iterator
     */
    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
        $this->iterator->rewind();
    }

    // overrides
    public function getIterator(): \Iterator
    {
        foreach ($this->cache as $key => $value) {
            yield $key => $value;
        }
        while ($this->iterator->valid()) {
            $key = $this->iterator->key();
            $value = $this->iterator->current();
            yield $key => $value;
            $this->cache[$key] = $value;
            $this->iterator->next();
        }
    }
}
