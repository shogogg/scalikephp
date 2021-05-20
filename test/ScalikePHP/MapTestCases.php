<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use BadMethodCallException;
use LogicException;
use ScalikePHP\Map;
use ScalikePHP\Seq;

/**
 * Tests for Map.
 */
trait MapTestCases
{
    /**
     * Create a Map for testing.
     *
     * @param array $values
     * @return \ScalikePHP\Map
     */
    abstract protected function map(array $values = []): Map;

    /**
     * Tests for Map::append().
     *
     * @see \ScalikePHP\ArrayMap::append()
     * @see \ScalikePHP\TraversableMap::append()
     * @noinspection PhpUnused
     */
    public function testAppend(): void
    {
        $map = $this->map(['Civic' => 'Honda']);
        Assert::same(
            ['Civic' => 'Honda', 'Levorg' => 'Subaru'],
            $map->append('Levorg', 'Subaru')->toAssoc()
        );
        Assert::same(
            ['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'],
            $map->append(['Levorg' => 'Subaru', 'Prius' => 'Toyota'])->toAssoc()
        );
    }

    /**
     * Tests for Map::contains().
     *
     * @see \ScalikePHP\ArrayMap::contains()
     * @see \ScalikePHP\TraversableMap::contains()
     * @noinspection PhpUnused
     */
    public function testContains(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru']);
        Assert::true($map->contains('Civic'));
        Assert::true($map->contains('Levorg'));
        Assert::false($map->contains('Prius'));
    }

    /**
     * Tests for Map::count().
     *
     * @see \ScalikePHP\ArrayMap::count()
     * @see \ScalikePHP\TraversableMap::count()
     * @noinspection PhpUnused
     */
    public function testCount(): void
    {
        Assert::same(0, $this->map()->count());
        Assert::same(1, $this->map(['a' => 1])->count());
        Assert::same(2, $this->map(['a' => 1, 'b' => 2])->count());
        Assert::same(3, $this->map(['a' => 1, 'b' => 2, 'c' => 3])->count());
    }

    /**
     * Tests for Map::drop().
     *
     * @see \ScalikePHP\Map::drop()
     * @noinspection PhpUnused
     */
    public function testDrop(): void
    {
        $map = $this->map(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5]);
        Assert::instanceOf(Map::class, $map->drop(3));
        Assert::same(['four' => 4, 'five' => 5], $map->drop(3)->toAssoc());
        Assert::instanceOf(Map::class, $map->drop(2));
        Assert::same(['three' => 3, 'four' => 4, 'five' => 5], $map->drop(2)->toAssoc());
        Assert::instanceOf(Map::class, $map->drop(1));
        Assert::same(['two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->drop(1)->toAssoc());
        Assert::instanceOf(Map::class, $map->drop(0));
        Assert::same(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->drop(0)->toAssoc());
        Assert::instanceOf(Map::class, $map->drop(-1));
        Assert::same(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->drop(-1)->toAssoc());
        Assert::instanceOf(Map::class, $map->drop(-2));
        Assert::same(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->drop(-2)->toAssoc());
    }

    /**
     * Tests for Map::each().
     *
     * @see \ScalikePHP\ArrayMap::each()
     * @see \ScalikePHP\TraversableMap::each()
     * @noinspection PhpUnused
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, 'spy'], func_get_args());
        };
        $spy->shouldReceive('spy')->with('Honda', 'Civic')->once();
        $spy->shouldReceive('spy')->with('Subaru', 'Levorg')->once();
        $spy->shouldReceive('spy')->with('Toyota', 'Prius')->once();
        $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'])->each($f);
    }

    /**
     * Tests for Map::exists().
     *
     * @see \ScalikePHP\ArrayMap::exists()
     * @see \ScalikePHP\TraversableMap::exists()
     * @noinspection PhpUnused
     */
    public function testExists(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $x): bool => strlen($x) === 5;
        $g = fn (string $x): bool => strlen($x) === 4;
        Assert::true($map->exists($f));
        Assert::false($map->exists($g));
    }

    /**
     * Tests for Map::filter().
     *
     * @see \ScalikePHP\ArrayMap::filter()
     * @see \ScalikePHP\TraversableMap::filter()
     * @noinspection PhpUnused
     */
    public function testFilter(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $x): bool => $x !== 'Honda';
        $g = fn (string $x): bool => $x !== 'Toyota';
        Assert::same(['Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->filter($f)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru'], $map->filter($g)->toAssoc());
    }

    /**
     * Tests for Map::filterNot().
     *
     * @see \ScalikePHP\ArrayMap::filterNot()
     * @see \ScalikePHP\TraversableMap::filterNot()
     * @noinspection PhpUnused
     */
    public function testFilterNot(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $x): bool => $x === 'Honda';
        $g = fn (string $x): bool => $x === 'Toyota';
        Assert::same(['Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->filterNot($f)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru'], $map->filterNot($g)->toAssoc());
    }

    /**
     * Tests for Map::find().
     *
     * @see \ScalikePHP\ArrayMap::find()
     * @see \ScalikePHP\TraversableMap::find()
     * @noinspection PhpUnused
     */
    public function testFind(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $x): bool => $x !== 'Honda';
        $g = fn (string $x): bool => $x === 'Ferrari';
        Assert::some(['Levorg', 'Subaru'], $map->find($f));
        Assert::none($map->find($g));
    }

    /**
     * Tests for Map::flatMap().
     *
     * @see \ScalikePHP\ArrayMap::flatMap()
     * @see \ScalikePHP\TraversableMap::flatMap()
     * @noinspection PhpUnused
     */
    public function testFlatMap(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $value, string $key): array => [
            $key => $value,
            strtoupper($key) => strtoupper($value),
        ];
        $g = fn (string $value, string $key): array => [
            $key => $value,
            strtolower($key) => strtolower($value),
        ];
        Assert::same(
            [
                'Civic' => 'Honda',
                'CIVIC' => 'HONDA',
                'Levorg' => 'Subaru',
                'LEVORG' => 'SUBARU',
                'Prius' => 'Toyota',
                'PRIUS' => 'TOYOTA',
            ],
            $map->flatMap($f)->toAssoc()
        );
        Assert::same(
            [
                'Civic' => 'Honda',
                'civic' => 'honda',
                'Levorg' => 'Subaru',
                'levorg' => 'subaru',
                'Prius' => 'Toyota',
                'prius' => 'toyota',
            ],
            $map->flatMap($g)->toAssoc()
        );
    }

    /**
     * Tests for Map::flatten().
     *
     * @see \ScalikePHP\ArrayMap::flatten()
     * @see \ScalikePHP\TraversableMap::flatten()
     * @noinspection PhpUnused
     */
    public function testFlatten(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::throws(
            LogicException::class,
            function () use ($map): void {
                $map->flatten();
            }
        );
    }

    /**
     * Tests for Map::fold().
     *
     * @see \ScalikePHP\ArrayMap::fold()
     * @see \ScalikePHP\TraversableMap::fold()
     * @noinspection PhpUnused
     */
    public function testFold(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $z, string $x): string => $z . '/' . $x;
        $g = fn (string $z, string $x): string => $x . '/' . $z;
        Assert::same('//Honda/Subaru/Toyota', $map->fold('/', $f));
        Assert::same('Toyota/Subaru/Honda//', $map->fold('/', $g));
    }

    /**
     * Tests for Map::forAll().
     *
     * @see \ScalikePHP\ArrayMap::forAll()
     * @see \ScalikePHP\TraversableMap::forAll()
     * @noinspection PhpUnused
     */
    public function testForAll(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $x): bool => strlen($x) > 4;
        $g = fn (string $x): bool => strlen($x) === 5;
        Assert::true($map->forAll($f));
        Assert::false($map->forAll($g));
    }

    /**
     * Tests for Map::get().
     *
     * @see \ScalikePHP\ArrayMap::get()
     * @see \ScalikePHP\TraversableMap::get()
     * @noinspection PhpUnused
     */
    public function testGet(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::some('Honda', $map->get('Civic'));
        Assert::some('Subaru', $map->get('Levorg'));
        Assert::some('Toyota', $map->get('Prius'));
        Assert::none($map->get('Fit'));
    }

    /**
     * Tests for Map::getOrElse().
     *
     * @see \ScalikePHP\ArrayMap::getOrElse()
     * @see \ScalikePHP\TraversableMap::getOrElse()
     * @noinspection PhpUnused
     */
    public function testGetOrElse(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $default = fn (): string => 'Undefined';
        Assert::same('Honda', $map->getOrElse('Civic', $default));
        Assert::same('Subaru', $map->getOrElse('Levorg', $default));
        Assert::same('Toyota', $map->getOrElse('Prius', $default));
        Assert::same('Undefined', $map->getOrElse('Fit', $default));
    }

    /**
     * Tests for Map::groupBy().
     *
     * @see \ScalikePHP\ArrayMap::groupBy()
     * @see \ScalikePHP\TraversableMap::groupBy()
     * @noinspection PhpUnused
     */
    public function testGroupBy(): void
    {
        $map = $this->map([
            'php' => ['name' => 'php', 'type' => 'language'],
            'python' => ['name' => 'python', 'type' => 'language'],
            'scala' => ['name' => 'scala', 'type' => 'language'],
            'symfony' => ['name' => 'symfony', 'type' => 'framework'],
            'django' => ['name' => 'django', 'type' => 'framework'],
            'playframework' => ['name' => 'playframework', 'type' => 'framework'],
        ]);
        $f = fn (array $item): string => $item['type'];
        $g = fn (Map $items): array => $items->toAssoc();
        $expected = [
            'language' => [
                'php' => ['name' => 'php', 'type' => 'language'],
                'python' => ['name' => 'python', 'type' => 'language'],
                'scala' => ['name' => 'scala', 'type' => 'language'],
            ],
            'framework' => [
                'symfony' => ['name' => 'symfony', 'type' => 'framework'],
                'django' => ['name' => 'django', 'type' => 'framework'],
                'playframework' => ['name' => 'playframework', 'type' => 'framework'],
            ],
        ];
        Assert::same($expected, $map->groupBy($f)->mapValues($g)->toAssoc());
        Assert::same($expected, $map->groupBy('type')->mapValues($g)->toAssoc());
    }

    /**
     * Tests for Map::head().
     *
     * @see \ScalikePHP\ArrayMap::head()
     * @see \ScalikePHP\TraversableMap::head()
     * @noinspection PhpUnused
     */
    public function testHead(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same(['Civic', 'Honda'], $map->head());
        Assert::throws(
            LogicException::class,
            function (): void {
                $this->map()->head();
            }
        );
    }

    /**
     * Tests for Map::headOption().
     *
     * @see \ScalikePHP\ArrayMap::headOption()
     * @see \ScalikePHP\TraversableMap::headOption()
     * @noinspection PhpUnused
     */
    public function testHeadOption(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::some(['Civic', 'Honda'], $map->headOption());
        Assert::none($this->map()->headOption());
    }

    /**
     * Tests for Map::isEmpty().
     *
     * @see \ScalikePHP\ArrayMap::isEmpty()
     * @see \ScalikePHP\TraversableMap::isEmpty()
     * @noinspection PhpUnused
     */
    public function testIsEmpty(): void
    {
        Assert::true($this->map()->isEmpty());
        Assert::false($this->map(['a' => 1])->isEmpty());
    }

    /**
     * Tests for Map::jsonSerialize().
     *
     * @see \ScalikePHP\ArrayMap::jsonSerialize()
     * @see \ScalikePHP\TraversableMap::jsonSerialize()
     * @noinspection PhpUnused
     */
    public function testJsonSerialize(): void
    {
        Assert::same(
            json_encode(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']),
            json_encode($this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']))
        );
    }

    /**
     * Tests for Map::last().
     *
     * @see \ScalikePHP\ArrayMap::last()
     * @see \ScalikePHP\TraversableMap::last()
     * @noinspection PhpUnused
     */
    public function testLast(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same(['Prius', 'Toyota'], $map->last());
        Assert::throws(
            LogicException::class,
            function (): void {
                $this->map()->last();
            }
        );
    }

    /**
     * Tests for Map::lastOption().
     *
     * @see \ScalikePHP\ArrayMap::lastOption()
     * @see \ScalikePHP\TraversableMap::lastOption()
     * @noinspection PhpUnused
     */
    public function testLastOption(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::some(['Prius', 'Toyota'], $map->lastOption());
        Assert::none($this->map()->lastOption());
    }

    /**
     * Tests for Map::map().
     *
     * @see \ScalikePHP\ArrayMap::map()
     * @see \ScalikePHP\TraversableMap::map()
     * @noinspection PhpUnused
     */
    public function testMap(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = fn (string $value, string $key): array => [
            strtoupper($value),
            strtoupper($key),
        ];
        $g = fn (string $value, string $key): array => [
            strtolower($key),
            strtolower($value),
        ];
        Assert::same(['HONDA' => 'CIVIC', 'SUBARU' => 'LEVORG', 'TOYOTA' => 'PRIUS'], $map->map($f)->toAssoc());
        Assert::same(['civic' => 'honda', 'levorg' => 'subaru', 'prius' => 'toyota'], $map->map($g)->toAssoc());
    }

    /**
     * Tests for Map::max().
     *
     * @see \ScalikePHP\ArrayMap::max()
     * @see \ScalikePHP\TraversableMap::max()
     * @noinspection PhpUnused
     */
    public function testMax(): void
    {
        Assert::same(['Z', 1], $this->map(['A' => 9, 'Z' => 1])->max());
        Assert::same([9, 'A'], $this->map([9 => 'A', 1 => 'Z'])->max());
    }

    /**
     * Tests for Map::maxBy().
     *
     * @see \ScalikePHP\ArrayMap::maxBy()
     * @see \ScalikePHP\TraversableMap::maxBy()
     * @noinspection PhpUnused
     */
    public function testMaxBy(): void
    {
        $map = $this->map([1 => 9, 2 => 8, 3 => 7, 4 => 6, 5 => 5, 6 => 4, 7 => 3, 8 => 2, 9 => 1]);
        $f = fn (int $value): int => $value;
        $g = fn (int $value, int $key): int => $key;
        Assert::same([1, 9], $map->maxBy($f));
        Assert::same([9, 1], $map->maxBy($g));
    }

    /**
     * Tests for Map::min().
     *
     * @see \ScalikePHP\ArrayMap::min()
     * @see \ScalikePHP\TraversableMap::min()
     * @noinspection PhpUnused
     */
    public function testMin(): void
    {
        Assert::same(['A', 9], $this->map(['A' => 9, 'Z' => 1])->min());
        Assert::same([1, 'Z'], $this->map([9 => 'A', 1 => 'Z'])->min());
    }

    /**
     * Tests for Map::minBy().
     *
     * @see \ScalikePHP\ArrayMap::minBy()
     * @see \ScalikePHP\TraversableMap::minBy()
     * @noinspection PhpUnused
     */
    public function testMinBy(): void
    {
        $map = $this->map([1 => 9, 2 => 8, 3 => 7, 4 => 6, 5 => 5, 6 => 4, 7 => 3, 8 => 2, 9 => 1]);
        $f = fn (int $value): int => $value;
        $g = fn (int $value, int $key): int => $key;
        Assert::same([9, 1], $map->minBy($f));
        Assert::same([1, 9], $map->minBy($g));
    }

    /**
     * Tests for Map::mkString().
     *
     * @see \ScalikePHP\ArrayMap::mkString()
     * @see \ScalikePHP\TraversableMap::mkString()
     * @noinspection PhpUnused
     */
    public function testMkString(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same('Civic => HondaLevorg => SubaruPrius => Toyota', $map->mkString());
        Assert::same('Civic => Honda,Levorg => Subaru,Prius => Toyota', $map->mkString(','));
        Assert::same('Civic => Honda, Levorg => Subaru, Prius => Toyota', $map->mkString(', '));
    }

    /**
     * Tests for Map::nonEmpty().
     *
     * @see \ScalikePHP\ArrayMap::nonEmpty()
     * @see \ScalikePHP\TraversableMap::nonEmpty()
     * @noinspection PhpUnused
     */
    public function testNonEmpty(): void
    {
        Assert::true($this->map(['a' => 1])->nonEmpty());
        Assert::false($this->map()->nonEmpty());
    }

    /**
     * Tests for Map::offsetExists().
     *
     * @see \ScalikePHP\ArrayMap::offsetExists()
     * @see \ScalikePHP\TraversableMap::offsetExists()
     * @noinspection PhpUnused
     */
    public function testOffsetExists(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::true(isset($map['Civic']));
        Assert::true(isset($map['Levorg']));
        Assert::true(isset($map['Prius']));
        Assert::false(isset($map['Fit']));
    }

    /**
     * Tests for Map::offsetGet().
     *
     * @see \ScalikePHP\ArrayMap::offsetGet()
     * @see \ScalikePHP\TraversableMap::offsetGet()
     * @noinspection PhpUnused
     */
    public function testOffsetGet(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same('Honda', $map['Civic']);
        Assert::same('Subaru', $map['Levorg']);
        Assert::same('Toyota', $map['Prius']);
    }

    /**
     * Tests for Map::offsetSet().
     *
     * @see \ScalikePHP\ArrayMap::offsetSet()
     * @see \ScalikePHP\TraversableMap::offsetSet()
     * @noinspection PhpUnused
     */
    public function testOffsetSet(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = function () use ($map): void {
            $map['Civic'] = 'HONDA';
        };
        $g = function () use ($map): void {
            $map['Fit'] = 'Honda';
        };
        Assert::throws(BadMethodCallException::class, $f);
        Assert::throws(BadMethodCallException::class, $g);
    }

    /**
     * Tests for Map::offsetUnset().
     *
     * @see \ScalikePHP\ArrayMap::offsetUnset()
     * @see \ScalikePHP\TraversableMap::offsetUnset()
     * @noinspection PhpUnused
     */
    public function testOffsetUnset(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        $f = function () use ($map): void {
            unset($map['Civic']);
        };
        $g = function () use ($map): void {
            unset($map['Fit']);
        };
        Assert::throws(BadMethodCallException::class, $f);
        Assert::throws(BadMethodCallException::class, $g);
    }

    /**
     * Tests for Map::partition().
     *
     * @see \ScalikePHP\Map::partition()
     * @noinspection PhpUnused
     */
    public function testPartition(): void
    {
        $map = $this->map(['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4]);
        $a = $map->partition(fn (int $x): bool => $x % 2 === 0);
        $b = $map->partition(fn (int $x): bool => $x % 2 !== 0);

        Assert::true(is_array($a));
        Assert::same(2, count($a));
        Assert::instanceOf(Map::class, $a[0]);
        Assert::instanceOf(Map::class, $a[1]);
        Assert::same(['B' => 2, 'D' => 4], $a[0]->toAssoc());
        Assert::same(['A' => 1, 'C' => 3], $a[1]->toAssoc());

        Assert::true(is_array($b));
        Assert::same(2, count($b));
        Assert::instanceOf(Map::class, $b[0]);
        Assert::instanceOf(Map::class, $b[1]);
        Assert::same(['A' => 1, 'C' => 3], $b[0]->toAssoc());
        Assert::same(['B' => 2, 'D' => 4], $b[1]->toAssoc());
    }

    /**
     * Tests for Map::size().
     *
     * @see \ScalikePHP\ArrayMap::size()
     * @see \ScalikePHP\TraversableMap::size()
     * @noinspection PhpUnused
     */
    public function testSize(): void
    {
        Assert::same(0, $this->map()->size());
        Assert::same(1, $this->map(['a' => 1])->size());
        Assert::same(2, $this->map(['a' => 1, 'b' => 2])->size());
        Assert::same(3, $this->map(['a' => 1, 'b' => 2, 'c' => 3])->size());
    }

    /**
     * Tests for Map::sum().
     *
     * @see \ScalikePHP\ArrayMap::sum()
     * @see \ScalikePHP\TraversableMap::sum()
     * @noinspection PhpUnused
     */
    public function testSum(): void
    {
        $f = function (): void {
            $this->map()->sum();
        };
        $g = function (): void {
            $this->map(['a' => 1, 'b' => 2, 'c' => 3])->sum();
        };
        Assert::throws(LogicException::class, $f);
        Assert::throws(LogicException::class, $g);
    }

    /**
     * Tests for Map::sumBy().
     *
     * @see \ScalikePHP\ArrayMap::sumBy()
     * @see \ScalikePHP\TraversableMap::sumBy()
     * @noinspection PhpUnused
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, int $value): int => $z + $value;
        Assert::same(0, $this->map()->sumBy($f));
        Assert::same(6, $this->map(['a' => 1, 'b' => 2, 'c' => 3])->sumBy($f));
    }

    /**
     * Tests for Map::tail().
     *
     * @see \ScalikePHP\Map::tail()
     * @noinspection PhpUnused
     */
    public function testTail(): void
    {
        $map = $this->map(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5]);
        Assert::instanceOf(Map::class, $map->drop(3));
        Assert::same(['two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->tail()->toAssoc());
        Assert::same(['three' => 3, 'four' => 4, 'five' => 5], $map->tail()->tail()->toAssoc());
        Assert::throws(
            LogicException::class,
            function (): void {
                Seq::empty()->tail();
            }
        );
    }

    /**
     * Tests for Map::take().
     *
     * @see \ScalikePHP\ArrayMap::take()
     * @see \ScalikePHP\TraversableMap::take()
     * @noinspection PhpUnused
     */
    public function testTake(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([], $map->take(0)->toAssoc());
        Assert::same(['Civic' => 'Honda'], $map->take(1)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru'], $map->take(2)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->take(3)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->take(4)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->take(100)->toAssoc());
    }

    /**
     * Tests for Map::takeRight().
     *
     * @see \ScalikePHP\ArrayMap::takeRight()
     * @see \ScalikePHP\TraversableMap::takeRight()
     * @noinspection PhpUnused
     */
    public function testTakeRight(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([], $map->takeRight(0)->toAssoc());
        Assert::same(['Prius' => 'Toyota'], $map->takeRight(1)->toAssoc());
        Assert::same(['Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->takeRight(2)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->takeRight(3)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->takeRight(4)->toAssoc());
        Assert::same(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota'], $map->takeRight(100)->toAssoc());
    }

    /**
     * Tests for Map::toArray().
     *
     * @see \ScalikePHP\ArrayMap::toArray()
     * @see \ScalikePHP\TraversableMap::toArray()
     * @noinspection PhpUnused
     */
    public function testToArray(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([['Civic', 'Honda'], ['Levorg', 'Subaru'], ['Prius', 'Toyota']], $map->toArray());
    }

    /**
     * Tests for Map::toSeq().
     *
     * @see \ScalikePHP\ArrayMap::toSeq()
     * @see \ScalikePHP\TraversableMap::toSeq()
     * @noinspection PhpUnused
     */
    public function testToSeq(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([['Civic', 'Honda'], ['Levorg', 'Subaru'], ['Prius', 'Toyota']], $map->toSeq()->toArray());
    }
}
