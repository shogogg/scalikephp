<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\Map;
use ScalikePHP\Seq;

/**
 * Tests for Maps.
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::append()
     * @covers \ScalikePHP\Implementations\TraversableMap::append()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::contains()
     * @covers \ScalikePHP\Implementations\TraversableMap::contains()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::count()
     * @covers \ScalikePHP\Implementations\TraversableMap::count()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::drop()
     * @covers \ScalikePHP\Implementations\TraversableMap::drop()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::each()
     * @covers \ScalikePHP\Implementations\TraversableMap::each()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::exists()
     * @covers \ScalikePHP\Implementations\TraversableMap::exists()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::filter()
     * @covers \ScalikePHP\Implementations\TraversableMap::filter()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::filterNot()
     * @covers \ScalikePHP\Implementations\TraversableMap::filterNot()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::find()
     * @covers \ScalikePHP\Implementations\TraversableMap::find()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::flatMap()
     * @covers \ScalikePHP\Implementations\TraversableMap::flatMap()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::flatten()
     * @covers \ScalikePHP\Implementations\TraversableMap::flatten()
     * @noinspection PhpUnused
     */
    public function testFlatten(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::throws(
            \LogicException::class,
            function () use ($map): void {
                $map->flatten();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::fold()
     * @covers \ScalikePHP\Implementations\TraversableMap::fold()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::forAll()
     * @covers \ScalikePHP\Implementations\TraversableMap::forAll()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::get()
     * @covers \ScalikePHP\Implementations\TraversableMap::get()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::getOrElse()
     * @covers \ScalikePHP\Implementations\TraversableMap::getOrElse()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::groupBy()
     * @covers \ScalikePHP\Implementations\TraversableMap::groupBy()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::head()
     * @covers \ScalikePHP\Implementations\TraversableMap::head()
     * @noinspection PhpUnused
     */
    public function testHead(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same(['Civic', 'Honda'], $map->head());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->map()->head();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::headOption()
     * @covers \ScalikePHP\Implementations\TraversableMap::headOption()
     * @noinspection PhpUnused
     */
    public function testHeadOption(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::some(['Civic', 'Honda'], $map->headOption());
        Assert::none($this->map()->headOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::isEmpty()
     * @covers \ScalikePHP\Implementations\TraversableMap::isEmpty()
     * @noinspection PhpUnused
     */
    public function testIsEmpty(): void
    {
        Assert::true($this->map()->isEmpty());
        Assert::false($this->map(['a' => 1])->isEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::jsonSerialize()
     * @covers \ScalikePHP\Implementations\TraversableMap::jsonSerialize()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::last()
     * @covers \ScalikePHP\Implementations\TraversableMap::last()
     * @noinspection PhpUnused
     */
    public function testLast(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same(['Prius', 'Toyota'], $map->last());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->map()->last();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::lastOption()
     * @covers \ScalikePHP\Implementations\TraversableMap::lastOption()
     * @noinspection PhpUnused
     */
    public function testLastOption(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::some(['Prius', 'Toyota'], $map->lastOption());
        Assert::none($this->map()->lastOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::map()
     * @covers \ScalikePHP\Implementations\TraversableMap::map()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::max()
     * @covers \ScalikePHP\Implementations\TraversableMap::max()
     * @noinspection PhpUnused
     */
    public function testMax(): void
    {
        Assert::same(['Z', 1], $this->map(['A' => 9, 'Z' => 1])->max());
        Assert::same([9, 'A'], $this->map([9 => 'A', 1 => 'Z'])->max());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::maxBy()
     * @covers \ScalikePHP\Implementations\TraversableMap::maxBy()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::min()
     * @covers \ScalikePHP\Implementations\TraversableMap::min()
     * @noinspection PhpUnused
     */
    public function testMin(): void
    {
        Assert::same(['A', 9], $this->map(['A' => 9, 'Z' => 1])->min());
        Assert::same([1, 'Z'], $this->map([9 => 'A', 1 => 'Z'])->min());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::minBy()
     * @covers \ScalikePHP\Implementations\TraversableMap::minBy()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::mkString()
     * @covers \ScalikePHP\Implementations\TraversableMap::mkString()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::nonEmpty()
     * @covers \ScalikePHP\Implementations\TraversableMap::nonEmpty()
     * @noinspection PhpUnused
     */
    public function testNonEmpty(): void
    {
        Assert::true($this->map(['a' => 1])->nonEmpty());
        Assert::false($this->map()->nonEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::offsetExists()
     * @covers \ScalikePHP\Implementations\TraversableMap::offsetExists()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::offsetGet()
     * @covers \ScalikePHP\Implementations\TraversableMap::offsetGet()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::offsetSet()
     * @covers \ScalikePHP\Implementations\TraversableMap::offsetSet()
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
        Assert::throws(\BadMethodCallException::class, $f);
        Assert::throws(\BadMethodCallException::class, $g);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::offsetUnset()
     * @covers \ScalikePHP\Implementations\TraversableMap::offsetUnset()
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
        Assert::throws(\BadMethodCallException::class, $f);
        Assert::throws(\BadMethodCallException::class, $g);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::partition()
     * @covers \ScalikePHP\Implementations\TraversableMap::partition()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::size()
     * @covers \ScalikePHP\Implementations\TraversableMap::size()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::sum()
     * @covers \ScalikePHP\Implementations\TraversableMap::sum()
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
        Assert::throws(\LogicException::class, $f);
        Assert::throws(\LogicException::class, $g);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::sumBy()
     * @covers \ScalikePHP\Implementations\TraversableMap::sumBy()
     * @noinspection PhpUnused
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, int $value): int => $z + $value;
        Assert::same(0, $this->map()->sumBy($f));
        Assert::same(6, $this->map(['a' => 1, 'b' => 2, 'c' => 3])->sumBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::tail()
     * @covers \ScalikePHP\Implementations\TraversableMap::tail()
     * @noinspection PhpUnused
     */
    public function testTail(): void
    {
        $map = $this->map(['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5]);
        Assert::instanceOf(Map::class, $map->drop(3));
        Assert::same(['two' => 2, 'three' => 3, 'four' => 4, 'five' => 5], $map->tail()->toAssoc());
        Assert::same(['three' => 3, 'four' => 4, 'five' => 5], $map->tail()->tail()->toAssoc());
        Assert::throws(
            \LogicException::class,
            function (): void {
                Seq::empty()->tail();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::take()
     * @covers \ScalikePHP\Implementations\TraversableMap::take()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::takeRight()
     * @covers \ScalikePHP\Implementations\TraversableMap::takeRight()
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
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::toArray()
     * @covers \ScalikePHP\Implementations\TraversableMap::toArray()
     * @noinspection PhpUnused
     */
    public function testToArray(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([['Civic', 'Honda'], ['Levorg', 'Subaru'], ['Prius', 'Toyota']], $map->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArrayMap::toSeq()
     * @covers \ScalikePHP\Implementations\TraversableMap::toSeq()
     * @noinspection PhpUnused
     */
    public function testToSeq(): void
    {
        $map = $this->map(['Civic' => 'Honda', 'Levorg' => 'Subaru', 'Prius' => 'Toyota']);
        Assert::same([['Civic', 'Honda'], ['Levorg', 'Subaru'], ['Prius', 'Toyota']], $map->toSeq()->toArray());
    }
}
