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
 * Tests for None.
 *
 * @see \ScalikePHP\None
 *
 * @internal
 * @coversNothing
 */
final class NoneTest extends TestCase
{
    /**
     * Tests for None::count().
     *
     * @see \ScalikePHP\None::count()
     */
    public function testCount(): void
    {
        Assert::same(0, Option::none()->count());
    }

    /**
     * Tests for None::drop().
     *
     * @see \ScalikePHP\None::drop()
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
     * Tests for None::each().
     *
     * @see \ScalikePHP\None::each()
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
     * Tests for Option::exists().
     *
     * @see \ScalikePHP\Option::exists()
     */
    public function testExists(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::false(Option::none()->exists($p));
    }

    /**
     * Tests for Option::filter().
     *
     * @see \ScalikePHP\Option::filter()
     */
    public function testFilter(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::none(Option::none()->filter($p));
    }

    /**
     * Tests for Option::filterNot().
     *
     * @see \ScalikePHP\Option::filterNot()
     */
    public function testFilterNot(): void
    {
        $p = fn (int $x): bool => $x !== 1;
        Assert::none(Option::none()->filterNot($p));
    }

    /**
     * Tests for None::find().
     *
     * @see \ScalikePHP\None::find()
     */
    public function testFind(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::none(Option::none()->filter($p));
    }

    /**
     * Tests for None::flatMap().
     *
     * @see \ScalikePHP\None::flatMap()
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
     * Tests for None::flatten().
     *
     * @see \ScalikePHP\None::flatten()
     */
    public function testFlatten(): void
    {
        Assert::none(Some::none()->flatten());
    }

    /**
     * Tests for None::forAll().
     *
     * @see \ScalikePHP\None::forAll()
     */
    public function testForAll(): void
    {
        $p = fn (int $x): bool => $x === 1;
        Assert::true(Option::none()->forAll($p));
    }

    /**
     * Tests for None::get().
     *
     * @see \ScalikePHP\None::get()
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
     * Tests for None::getOrElse().
     *
     * @see \ScalikePHP\None::getOrElse()
     */
    public function testGetOrElse(): void
    {
        $spy = self::spy();
        $f = fn () => call_user_func_array([$spy, 'spy'], func_get_args());
        $spy->shouldReceive('spy')->andReturn('abc');
        Assert::same('abc', Option::none()->getOrElse($f));
    }

    /**
     * Tests for Some::getOrElseValue().
     *
     * @see \ScalikePHP\Some::getOrElseValue()
     */
    public function testGetOrElseValue(): void
    {
        Assert::same(0, Option::none()->getOrElseValue(0));
        Assert::same('xyz', Option::none()->getOrElseValue('xyz'));
    }

    /**
     * Tests for None::groupBy().
     *
     * @see \ScalikePHP\None::groupBy()
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
     * Tests for None::head().
     *
     * @see \ScalikePHP\None::head()
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
     * Tests for None::headOption().
     *
     * @see \ScalikePHP\None::headOption()
     */
    public function testHeadOption(): void
    {
        Assert::none(Option::none()->headOption());
    }

    /**
     * Tests for None::isDefined().
     *
     * @see \ScalikePHP\None::isDefined()
     */
    public function testIsDefined(): void
    {
        Assert::false(Option::none()->isDefined());
    }

    /**
     * Tests for None::isEmpty().
     *
     * @see \ScalikePHP\None::isEmpty()
     */
    public function testIsEmpty(): void
    {
        Assert::true(Option::none()->isEmpty());
    }

    /**
     * Tests for None::last().
     *
     * @see \ScalikePHP\None::last()
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
     * Tests for None::lastOption().
     *
     * @see \ScalikePHP\None::lastOption()
     */
    public function testLastOption(): void
    {
        Assert::none(Option::none()->lastOption());
    }

    /**
     * Tests for None::map().
     *
     * @see \ScalikePHP\None::map()
     */
    public function testMap(): void
    {
        $f = fn (int $x): int => $x * 2;
        Assert::none(Option::none()->map($f));
    }

    /**
     * Tests for None::max().
     *
     * @see \ScalikePHP\None::max()
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
     * Tests for None::maxBy().
     *
     * @see \ScalikePHP\None::maxBy()
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
     * Tests for None::min().
     *
     * @see \ScalikePHP\None::min()
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
     * Tests for None::minBy().
     *
     * @see \ScalikePHP\None::minBy()
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
     * Tests for None::mkString().
     *
     * @see \ScalikePHP\None::mkString()
     */
    public function testMkString(): void
    {
        Assert::same('', Option::none()->mkString());
        Assert::same('', Option::none()->mkString('-'));
    }

    /**
     * Tests for None::nonEmpty().
     *
     * @see \ScalikePHP\None::nonEmpty()
     */
    public function testNonEmpty(): void
    {
        Assert::false(Option::none()->nonEmpty());
    }

    /**
     * Tests for None::orElse().
     *
     * @see \ScalikePHP\None::orElse()
     */
    public function testOrElse(): void
    {
        $f = fn (): Option => Option::from('xyz');
        Assert::some('xyz', Option::none()->orElse($f));
    }

    /**
     * Tests for None::orNull().
     *
     * @see \ScalikePHP\None::orNull()
     */
    public function testOrNull(): void
    {
        Assert::same(null, Option::none()->orNull());
    }

    /**
     * Tests for None::partition().
     *
     * @see \ScalikePHP\None::partition()
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
     * Tests for None::pick().
     *
     * @see \ScalikePHP\None::pick()
     */
    public function testPick(): void
    {
        Assert::none(Option::none()->pick('abc'));
    }

    /**
     * Tests for None::size().
     *
     * @see \ScalikePHP\None::size()
     */
    public function testSize(): void
    {
        Assert::same(0, Option::none()->size());
    }

    /**
     * Tests for None::sum().
     *
     * @see \ScalikePHP\None::sum()
     */
    public function testSum(): void
    {
        Assert::same(0, Option::none()->sum());
    }

    /**
     * Tests for None::sumBy().
     *
     * @see \ScalikePHP\None::sumBy()
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, int $value): int => $z + $value;
        Assert::same(0, Option::none()->sumBy($f));
    }

    /**
     * Tests for None::tail().
     *
     * @see \ScalikePHP\None::tail()
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
     * Tests for None::take().
     *
     * @see \ScalikePHP\None::take()
     */
    public function testTake(): void
    {
        Assert::instanceOf(None::class, Option::none()->take(1));
        Assert::instanceOf(None::class, Option::none()->take(2));
        Assert::same([], Option::none()->take(1)->toArray());
        Assert::same([], Option::none()->take(2)->toArray());
    }

    /**
     * Tests for None::takeRight().
     *
     * @see \ScalikePHP\None::takeRight()
     */
    public function testTakeRight(): void
    {
        Assert::instanceOf(None::class, Option::none()->takeRight(1));
        Assert::instanceOf(None::class, Option::none()->takeRight(2));
        Assert::same([], Option::none()->takeRight(1)->toArray());
        Assert::same([], Option::none()->takeRight(2)->toArray());
    }

    /**
     * Tests for None::toArray().
     *
     * @see \ScalikePHP\None::toArray()
     */
    public function testToArray(): void
    {
        Assert::same([], Option::none()->toArray());
    }

    /**
     * Tests for None::toSeq().
     *
     * @see \ScalikePHP\None::toSeq()
     */
    public function testToSeq(): void
    {
        Assert::instanceOf(Seq::class, Option::none()->toSeq());
        Assert::same([], Option::none()->toSeq()->toArray());
    }
}
