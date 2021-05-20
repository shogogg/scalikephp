<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use LogicException;
use ScalikePHP\Option;
use ScalikePHP\Seq;
use ScalikePHP\Some;

/**
 * Tests for {@link \ScalikePHP\Some}.
 *
 * @internal
 */
final class SomeTest extends TestCase
{
    /**
     * @test
     * @covers \ScalikePHP\Some::count()
     */
    public function testCount(): void
    {
        Assert::same(1, Some::create(1)->count());
        Assert::same(1, Some::create(2)->count());
        Assert::same(1, Some::create('abc')->count());
        Assert::same(1, Some::create('xyz')->count());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::create()
     */
    public function testCreate(): void
    {
        Assert::instanceOf(Some::class, Some::create(1));
        Assert::instanceOf(Some::class, Some::create('abc'));
        Assert::instanceOf(Some::class, Some::create(null));
        Assert::same(1, Some::create(1)->get());
        Assert::same('abc', Some::create('abc')->get());
        Assert::same(null, Some::create(null)->get());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::drop()
     */
    public function testDrop(): void
    {
        $some = Option::some(1);
        // 1以上
        for ($i = 2; $i > 0; --$i) {
            Assert::instanceOf(Seq::class, $some->drop($i));
            Assert::same([], $some->drop($i)->toArray());
        }
        // 0以下
        for ($i = 0; $i >= -2; --$i) {
            Assert::instanceOf(Seq::class, $some->drop($i));
            Assert::same([1], $some->drop($i)->toArray());
        }
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::each()
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, 'spy'], func_get_args());
        };
        $spy->shouldReceive('spy')->with(1, 0)->once();
        Some::create(1)->each($f);
    }

    /**
     * Tests for Option::exists().
     *
     * @covers \ScalikePHP\Option::exists()
     */
    public function testExists(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::true(Some::create(1)->exists($p));
        Assert::false(Some::create(2)->exists($p));
    }

    /**
     * Tests for Option::filter().
     *
     * @covers \ScalikePHP\Option::filter()
     */
    public function testFilter(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::some(1, Some::create(1)->filter($p));
        Assert::none(Some::create(2)->filter($p));
    }

    /**
     * Tests for Option::filterNot().
     *
     * @covers \ScalikePHP\Option::filterNot()
     */
    public function testFilterNot(): void
    {
        $p = fn (int $x): bool => $x !== 1;
        Assert::some(1, Some::create(1)->filterNot($p));
        Assert::none(Some::create(2)->filterNot($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::find()
     */
    public function testFind(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::some(1, Some::create(1)->filter($p));
        Assert::none(Some::create(2)->filter($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::flatMap()
     */
    public function testFlatMap(): void
    {
        $returnsSome = fn ($x): Option => Option::from($x * 2);
        $returnsNone = fn (): Option => Option::none();
        $returnsArray = fn ($x): array => [$x * 2];
        Assert::some(2, Some::create(1)->flatMap($returnsSome));
        Assert::none(Some::create(1)->flatMap($returnsNone));
        Assert::throws(
            LogicException::class,
            function () use ($returnsArray): void {
                Some::create(1)->flatMap($returnsArray);
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::flatten()
     */
    public function testFlatten(): void
    {
        Assert::some('abc', Some::create(Some::create('abc'))->flatten());
        Assert::none(Some::create(Option::none())->flatten());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::forAll()
     */
    public function testForAll(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::true(Some::create(1)->forAll($p));
        Assert::false(Some::create(2)->forAll($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::get()
     */
    public function testGet(): void
    {
        Assert::same(1, Some::create(1)->get());
        Assert::same('abc', Some::create('abc')->get());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::getOrElse()
     */
    public function testGetOrElse(): void
    {
        $spy = self::spy();
        $f = fn () => call_user_func_array([$spy, 'spy'], func_get_args());
        $spy->shouldNotReceive('spy')->andReturn('abc');
        Assert::same(1, Some::create(1)->getOrElse($f));
        Assert::same('abc', Some::create('abc')->getOrElse($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::getOrElseValue()
     */
    public function testGetOrElseValue(): void
    {
        Assert::same(1, Some::create(1)->getOrElseValue(0));
        Assert::same('abc', Some::create('abc')->getOrElseValue('xyz'));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::groupBy()
     */
    public function testGroupBy(): void
    {
        $key = 'abc';
        $value = 'xyz';
        $array = [$key => $value];
        $some = Some::create($array);
        $closure = fn (array $x): string => $x[$key];
        Assert::same(1, $some->groupBy($key)->size());
        Assert::same(1, $some->groupBy($closure)->size());
        Assert::same([$value], $some->groupBy($key)->keys()->toArray());
        Assert::same([$value], $some->groupBy($closure)->keys()->toArray());
        Assert::same([$array], $some->groupBy($key)->get($value)->get()->toArray());
        Assert::same([$array], $some->groupBy($closure)->get($value)->get()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::head()
     */
    public function testHead(): void
    {
        Assert::same(1, Some::create(1)->head());
        Assert::same('abc', Some::create('abc')->head());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::headOption()
     */
    public function testHeadOption(): void
    {
        Assert::some(1, Some::create(1)->headOption());
        Assert::some('abc', Some::create('abc')->headOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::isDefined()
     */
    public function testIsDefined(): void
    {
        Assert::true(Some::create(1)->isDefined());
        Assert::true(Some::create('abc')->isDefined());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::isEmpty()
     */
    public function testIsEmpty(): void
    {
        Assert::false(Some::create(1)->isEmpty());
        Assert::false(Some::create('abc')->isEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::last()
     */
    public function testLast(): void
    {
        Assert::same(1, Some::create(1)->last());
        Assert::same('abc', Some::create('abc')->last());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::lastOption()
     */
    public function testLastOption(): void
    {
        Assert::some(1, Some::create(1)->lastOption());
        Assert::some('abc', Some::create('abc')->lastOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::map()
     */
    public function testMap(): void
    {
        $f = fn (int $x): int => $x * 2;
        Assert::some(2, Some::create(1)->map($f));
        Assert::some(4, Some::create(2)->map($f));
        Assert::some(6, Some::create(3)->map($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::max()
     */
    public function testMax(): void
    {
        Assert::same(1, Some::create(1)->max());
        Assert::same('abc', Some::create('abc')->max());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::maxBy()
     */
    public function testMaxBy(): void
    {
        $f = fn ($x): string => (string)$x;
        Assert::same(1, Some::create(1)->maxBy($f));
        Assert::same('abc', Some::create('abc')->maxBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::min()
     */
    public function testMin(): void
    {
        Assert::same(1, Some::create(1)->min());
        Assert::same('abc', Some::create('abc')->min());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::minBy()
     */
    public function testMinBy(): void
    {
        $f = fn ($x): string => (string)$x;
        Assert::same(1, Some::create(1)->minBy($f));
        Assert::same('abc', Some::create('abc')->minBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::mkString()
     */
    public function testMkString(): void
    {
        Assert::same('1', Some::create(1)->mkString());
        Assert::same('abc', Some::create('abc')->mkString());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::nonEmpty()
     */
    public function testNonEmpty(): void
    {
        Assert::true(Some::create(1)->nonEmpty());
        Assert::true(Some::create('abc')->nonEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::orElse()
     */
    public function testOrElse(): void
    {
        $a = Some::create(1);
        $b = Some::create('abc');
        $f = fn (): string => 'xyz';
        Assert::same($a, $a->orElse($f));
        Assert::same($b, $b->orElse($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::orNull()
     */
    public function testOrNull(): void
    {
        Assert::same(1, Some::create(1)->orNull());
        Assert::same('abc', Some::create('abc')->orNull());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::partition()
     */
    public function testPartition(): void
    {
        $a = Option::some(1)->partition(fn (int $x): bool => $x === 1);
        $b = Option::some(1)->partition(fn (int $x): bool => $x !== 1);

        Assert::true(is_array($a));
        Assert::same(2, count($a));
        Assert::instanceOf(Seq::class, $a[0]);
        Assert::instanceOf(Seq::class, $a[1]);
        Assert::same([1], $a[0]->toArray());
        Assert::true($a[1]->isEmpty());

        Assert::true(is_array($b));
        Assert::same(2, count($b));
        Assert::instanceOf(Seq::class, $b[0]);
        Assert::instanceOf(Seq::class, $b[1]);
        Assert::true($b[0]->isEmpty());
        Assert::same([1], $b[1]->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::pick()
     */
    public function testPick(): void
    {
        Assert::some('xyz', Some::create(['abc' => 'xyz'])->pick('abc'));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::size()
     */
    public function testSize(): void
    {
        Assert::same(1, Some::create(1)->size());
        Assert::same(1, Some::create(2)->size());
        Assert::same(1, Some::create('abc')->size());
        Assert::same(1, Some::create('xyz')->size());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::sum()
     */
    public function testSum(): void
    {
        Assert::same(1, Some::create(1)->sum());
        Assert::same(2, Some::create(2)->sum());
        Assert::same('abc', Some::create('abc')->sum());
        Assert::same('xyz', Some::create('xyz')->sum());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::sumBy()
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, int $value): int => $z + $value;
        Assert::same(1, Some::create(1)->sumBy($f));
        Assert::same(2, Some::create(2)->sumBy($f));
        Assert::same('abc', Some::create('abc')->sumBy($f));
        Assert::same('xyz', Some::create('xyz')->sumBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::tail()
     */
    public function testTail(): void
    {
        Assert::instanceOf(Seq::class, Some::create(1)->tail());
        Assert::same([], Some::create(1)->tail()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::take()
     */
    public function testTake(): void
    {
        Assert::instanceOf(Some::class, Some::create(1)->take(1));
        Assert::instanceOf(Some::class, Some::create('abc')->take(1));
        Assert::same([1], Some::create(1)->take(1)->toArray());
        Assert::same([1], Some::create(1)->take(2)->toArray());
        Assert::same(['abc'], Some::create('abc')->take(1)->toArray());
        Assert::same(['abc'], Some::create('abc')->take(2)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::takeRight()
     */
    public function testTakeRight(): void
    {
        Assert::instanceOf(Some::class, Some::create(1)->takeRight(1));
        Assert::instanceOf(Some::class, Some::create('abc')->takeRight(1));
        Assert::same([1], Some::create(1)->takeRight(1)->toArray());
        Assert::same([1], Some::create(1)->takeRight(2)->toArray());
        Assert::same(['abc'], Some::create('abc')->takeRight(1)->toArray());
        Assert::same(['abc'], Some::create('abc')->takeRight(2)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::toArray()
     */
    public function testToArray(): void
    {
        Assert::same([1], Some::create(1)->toArray());
        Assert::same(['abc'], Some::create('abc')->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::toSeq()
     */
    public function testToSeq(): void
    {
        Assert::instanceOf(Seq::class, Some::create(1)->toSeq());
        Assert::instanceOf(Seq::class, Some::create('abc')->toSeq());
        Assert::same([1], Some::create(1)->toSeq()->toArray());
        Assert::same(['abc'], Some::create('abc')->toSeq()->toArray());
    }
}
