<?php
namespace Test\ScalikePHP;

use ScalikePHP\Option;
use ScalikePHP\Seq;
use ScalikePHP\Some;

/**
 * Tests for None.
 *
 * @see \ScalikePHP\None
 */
class NoneTest extends TestCase
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
     * Tests for None::each().
     *
     * @see \ScalikePHP\None::each()
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, "spy"], func_get_args());
        };
        $spy->shouldNotReceive("spy");
        Option::none()->each($f);
    }

    /**
     * Tests for Option::exists().
     *
     * @see \ScalikePHP\Option::exists()
     */
    public function testExists(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::false(Option::none()->exists($p));
    }

    /**
     * Tests for Option::filter().
     *
     * @see \ScalikePHP\Option::filter()
     */
    public function testFilter(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::none(Option::none()->filter($p));
    }

    /**
     * Tests for Option::filterNot().
     *
     * @see \ScalikePHP\Option::filterNot()
     */
    public function testFilterNot(): void
    {
        $p = function (int $x): bool {
            return $x !== 1;
        };
        Assert::none(Option::none()->filterNot($p));
    }

    /**
     * Tests for None::find().
     *
     * @see \ScalikePHP\None::find()
     */
    public function testFind(): void
    {
        $p = function (int $x): bool {
            return $x === 1;
        };
        Assert::none(Option::none()->filter($p));
    }

    /**
     * Tests for None::flatMap().
     *
     * @see \ScalikePHP\None::flatMap()
     */
    public function testFlatMap(): void
    {
        $returnsSome = function ($x): Option {
            return Option::from($x * 2);
        };
        $returnsNone = function (): Option {
            return Option::none();
        };
        $returnsArray = function ($x): array {
            return [$x * 2];
        };
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
        $p = function (int $x): bool {
            return $x === 1;
        };
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
            \LogicException::class,
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
        $f = function () use ($spy) {
            return call_user_func_array([$spy, "spy"], func_get_args());
        };
        $spy->shouldReceive("spy")->andReturn("abc");
        Assert::same("abc", Option::none()->getOrElse($f));
    }

    /**
     * Tests for None::groupBy().
     *
     * @see \ScalikePHP\None::groupBy()
     */
    public function testGroupBy(): void
    {
        $key = "abc";
        $none = Option::none();
        $closure = function (array $x) use ($key): string {
            return $x[$key];
        };
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
            \LogicException::class,
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
            \LogicException::class,
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
        $f = function (int $x): int {
            return $x * 2;
        };
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
            \LogicException::class,
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
            \LogicException::class,
            function (): void {
                $f = function ($x): string {
                    return strval($x);
                };
                Option::none()->maxBy($f);
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
            \LogicException::class,
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
            \LogicException::class,
            function (): void {
                $f = function ($x): string {
                    return strval($x);
                };
                Option::none()->minBy($f);
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
        Assert::same("", Option::none()->mkString(""));
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
        $f = function (): Option {
            return Option::from("xyz");
        };
        Assert::some("xyz", Option::none()->orElse($f));
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
     * Tests for None::pick().
     *
     * @see \ScalikePHP\None::pick()
     */
    public function testPick(): void
    {
        Assert::none(Option::none()->pick("abc"));
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
     * Tests for None::take().
     *
     * @see \ScalikePHP\None::take()
     */
    public function testTake(): void
    {
        Assert::instanceOf(Seq::class, Option::none()->take(1));
        Assert::instanceOf(Seq::class, Option::none()->take(2));
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
        Assert::instanceOf(Seq::class, Option::none()->takeRight(1));
        Assert::instanceOf(Seq::class, Option::none()->takeRight(2));
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
