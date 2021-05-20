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
use ScalikePHP\None;
use ScalikePHP\Option;
use ScalikePHP\Seq;
use ScalikePHP\Some;

/**
 * Tests for {@link \ScalikePHP\None}.
 *
 * @internal
 */
final class NoneTest extends TestCase
{
    /**
     * @test
     * @covers \ScalikePHP\None::count()
     */
    public function testCount(): void
    {
        Assert::same(0, Option::none()->count());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::drop()
     */
    public function testDrop(): void
    {
        $none = Option::none();
        for ($i = 2; $i >= -2; --$i) {
            Assert::instanceOf(Seq::class, $none->drop($i));
            Assert::same([], $none->drop($i)->toArray());
        }
    }

    /**
     * @test
     * @covers \ScalikePHP\None::each()
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, 'spy'], func_get_args());
        };
        $spy->shouldNotReceive('spy');
        Option::none()->each($f);
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::exists()
     */
    public function testExists(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::false(Option::none()->exists($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::filter()
     */
    public function testFilter(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::none(Option::none()->filter($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::filterNot()
     */
    public function testFilterNot(): void
    {
        $p = fn (int $x): bool => $x !== 1;
        Assert::none(Option::none()->filterNot($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::find()
     */
    public function testFind(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::none(Option::none()->filter($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::flatMap()
     */
    public function testFlatMap(): void
    {
        $returnsSome = fn ($x): Option => Option::from($x * 2);
        $returnsNone = fn (): Option => Option::none();
        $returnsArray = fn ($x): array => [$x * 2];
        Assert::none(Option::none()->flatMap($returnsSome));
        Assert::none(Option::none()->flatMap($returnsNone));
        Assert::none(Option::none()->flatMap($returnsArray));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::flatten()
     */
    public function testFlatten(): void
    {
        Assert::none(Some::none()->flatten());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::forAll()
     */
    public function testForAll(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::true(Option::none()->forAll($p));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::get()
     */
    public function testGet(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->get();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::getOrElse()
     */
    public function testGetOrElse(): void
    {
        $spy = self::spy();
        $f = fn () => call_user_func_array([$spy, 'spy'], func_get_args());
        $spy->shouldReceive('spy')->andReturn('abc');
        Assert::same('abc', Option::none()->getOrElse($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Some::getOrElseValue()
     */
    public function testGetOrElseValue(): void
    {
        Assert::same(0, Option::none()->getOrElseValue(0));
        Assert::same('xyz', Option::none()->getOrElseValue('xyz'));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::groupBy()
     */
    public function testGroupBy(): void
    {
        $key = 'abc';
        $none = Option::none();
        $closure = fn (array $x): string => $x[$key];
        Assert::same(0, $none->groupBy($key)->size());
        Assert::same(0, $none->groupBy($closure)->size());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::head()
     */
    public function testHead(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->head();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::headOption()
     */
    public function testHeadOption(): void
    {
        Assert::none(Option::none()->headOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::isDefined()
     */
    public function testIsDefined(): void
    {
        Assert::false(Option::none()->isDefined());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::isEmpty()
     */
    public function testIsEmpty(): void
    {
        Assert::true(Option::none()->isEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::last()
     */
    public function testLast(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->last();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::lastOption()
     */
    public function testLastOption(): void
    {
        Assert::none(Option::none()->lastOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::map()
     */
    public function testMap(): void
    {
        $f = fn (int $x): int => $x * 2;
        Assert::none(Option::none()->map($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::max()
     */
    public function testMax(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->max();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::maxBy()
     */
    public function testMaxBy(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->maxBy(fn ($x): string => (string)$x);
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::min()
     */
    public function testMin(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->min();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::minBy()
     */
    public function testMinBy(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->minBy(fn ($x): string => (string)$x);
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::mkString()
     */
    public function testMkString(): void
    {
        Assert::same('', Option::none()->mkString());
        Assert::same('', Option::none()->mkString('-'));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::nonEmpty()
     */
    public function testNonEmpty(): void
    {
        Assert::false(Option::none()->nonEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::orElse()
     */
    public function testOrElse(): void
    {
        $f = fn (): Option => Option::from('xyz');
        Assert::some('xyz', Option::none()->orElse($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::orNull()
     */
    public function testOrNull(): void
    {
        Assert::same(null, Option::none()->orNull());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::partition()
     */
    public function testPartition(): void
    {
        $a = Option::none()->partition(fn (int $x): bool => $x === 1);
        $b = Option::none()->partition(fn (int $x): bool => $x !== 1);

        Assert::true(is_array($a));
        Assert::same(2, count($a));
        Assert::instanceOf(Seq::class, $a[0]);
        Assert::instanceOf(Seq::class, $a[1]);
        Assert::true($a[0]->isEmpty());
        Assert::true($a[1]->isEmpty());

        Assert::true(is_array($b));
        Assert::same(2, count($b));
        Assert::instanceOf(Seq::class, $b[0]);
        Assert::instanceOf(Seq::class, $b[1]);
        Assert::true($b[0]->isEmpty());
        Assert::true($b[1]->isEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::pick()
     */
    public function testPick(): void
    {
        Assert::none(Option::none()->pick('abc'));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::size()
     */
    public function testSize(): void
    {
        Assert::same(0, Option::none()->size());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::sum()
     */
    public function testSum(): void
    {
        Assert::same(0, Option::none()->sum());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::sumBy()
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, int $value): int => $z + $value;
        Assert::same(0, Option::none()->sumBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\None::tail()
     */
    public function testTail(): void
    {
        Assert::throws(
            LogicException::class,
            function (): void {
                Option::none()->tail();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\None::take()
     */
    public function testTake(): void
    {
        Assert::instanceOf(None::class, Option::none()->take(1));
        Assert::instanceOf(None::class, Option::none()->take(2));
        Assert::same([], Option::none()->take(1)->toArray());
        Assert::same([], Option::none()->take(2)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::takeRight()
     */
    public function testTakeRight(): void
    {
        Assert::instanceOf(None::class, Option::none()->takeRight(1));
        Assert::instanceOf(None::class, Option::none()->takeRight(2));
        Assert::same([], Option::none()->takeRight(1)->toArray());
        Assert::same([], Option::none()->takeRight(2)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::toArray()
     */
    public function testToArray(): void
    {
        Assert::same([], Option::none()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\None::toSeq()
     */
    public function testToSeq(): void
    {
        Assert::instanceOf(Seq::class, Option::none()->toSeq());
        Assert::same([], Option::none()->toSeq()->toArray());
    }
}
