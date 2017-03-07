<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace Test\ScalikePHP;

use ScalikePHP\Seq;

/**
 * Tests for Seq.
 */
trait SeqTestCases
{

    /**
     * Create a Seq for testing.
     *
     * @param array $values
     * @return \ScalikePHP\Seq
     */
    abstract protected function seq(... $values): Seq;

    /**
     * Tests for Seq::append().
     *
     * @see \ScalikePHP\ArraySeq::append()
     * @see \ScalikePHP\TraversableSeq::append()
     */
    public function testAppend(): void
    {
        $seq = $this->seq("foo");
        Assert::same(["foo"], $seq->toArray());
        Assert::same(["foo", "bar"], $seq->append(["bar"])->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->append(["bar", "baz"])->toArray());
    }

    /**
     * Tests for Seq::contains().
     *
     * @see \ScalikePHP\ArraySeq::contains()
     * @see \ScalikePHP\TraversableSeq::contains()
     */
    public function testContains(): void
    {
        $seq = $this->seq("foo", "bar");
        Assert::true($seq->contains("foo"));
        Assert::true($seq->contains("bar"));
        Assert::false($seq->contains("baz"));
    }

    /**
     * Tests for Seq::count().
     *
     * @see \ScalikePHP\ArraySeq::count()
     * @see \ScalikePHP\TraversableSeq::count()
     */
    public function testCount(): void
    {
        Assert::same(0, ($this->seq())->count());
        Assert::same(1, ($this->seq("foo"))->count());
        Assert::same(2, ($this->seq("foo", "bar"))->count());
        Assert::same(3, ($this->seq("foo", "bar", "baz"))->count());
    }

    /**
     * Tests for Seq::distinct().
     *
     * @see \ScalikePHP\ArraySeq::distinct()
     * @see \ScalikePHP\TraversableSeq::distinct()
     */
    public function testDistinct(): void
    {
        $seq = $this->seq("foo", "bar", "foo", "baz", "bar", "foo", "baz");
        Assert::same(["foo", "bar", "baz"], $seq->distinct()->toArray());
    }

    /**
     * Tests for Seq::each().
     *
     * @see \ScalikePHP\ArraySeq::each()
     * @see \ScalikePHP\TraversableSeq::each()
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, "spy"], func_get_args());
        };
        $spy->shouldReceive("spy")->with(1)->once();
        $spy->shouldReceive("spy")->with(2)->once();
        $spy->shouldReceive("spy")->with(3)->once();
        $this->seq(1, 2, 3)->each($f);
    }

    /**
     * Tests for Seq::exists().
     *
     * @see \ScalikePHP\ArraySeq::exists()
     * @see \ScalikePHP\TraversableSeq::exists()
     */
    public function testExists(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function (string $x): bool {
            return strlen($x) === 3;
        };
        $g = function (string $x): bool {
            return strlen($x) === 4;
        };
        Assert::true($seq->exists($f));
        Assert::false($seq->exists($g));
    }

    /**
     * Tests for Seq::filter().
     *
     * @see \ScalikePHP\ArraySeq::filter()
     * @see \ScalikePHP\TraversableSeq::filter()
     */
    public function testFilter(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function (string $x): bool {
            return $x !== "foo";
        };
        $g = function (string $x): bool {
            return $x !== "baz";
        };
        Assert::same(["bar", "baz"], $seq->filter($f)->toArray());
        Assert::same(["foo", "bar"], $seq->filter($g)->toArray());
    }

    /**
     * Tests for Seq::filterNot().
     *
     * @see \ScalikePHP\ArraySeq::filterNot()
     * @see \ScalikePHP\TraversableSeq::filterNot()
     */
    public function testFilterNot(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function (string $x): bool {
            return $x === "foo";
        };
        $g = function (string $x): bool {
            return $x === "baz";
        };
        Assert::same(["bar", "baz"], $seq->filterNot($f)->toArray());
        Assert::same(["foo", "bar"], $seq->filterNot($g)->toArray());
    }

    /**
     * Tests for Seq::find().
     *
     * @see \ScalikePHP\ArraySeq::find()
     * @see \ScalikePHP\TraversableSeq::find()
     */
    public function testFind(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function (string $x): bool {
            return $x !== "foo";
        };
        $g = function (string $x): bool {
            return $x === "FizzBuzz";
        };
        Assert::some("bar", $seq->find($f));
        Assert::none($seq->find($g));
    }

    /**
     * Tests for Seq::flatMap().
     *
     * @see \ScalikePHP\ArraySeq::flatMap()
     * @see \ScalikePHP\TraversableSeq::flatMap()
     */
    public function testFlatMap(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function (string $x): array {
            return [$x, strtoupper($x)];
        };
        $g = function (string $x): Seq {
            return $this->seq($x, ucfirst($x));
        };
        Assert::same(["foo", "FOO", "bar", "BAR", "baz", "BAZ"], $seq->flatMap($f)->toArray());
        Assert::same(["foo", "Foo", "bar", "Bar", "baz", "Baz"], $seq->flatMap($g)->toArray());
    }

    /**
     * Tests for Seq::flatten().
     *
     * @see \ScalikePHP\ArraySeq::flatten()
     * @see \ScalikePHP\TraversableSeq::flatten()
     */
    public function testFlatten(): void
    {
        $seq = $this->seq(["foo", "bar", "baz"], ["Fizz", "Buzz", "FizzBuzz"]);
        Assert::same(["foo", "bar", "baz", "Fizz", "Buzz", "FizzBuzz"], $seq->flatten()->toArray());
    }

    /**
     * Tests for Seq::fold().
     *
     * @see \ScalikePHP\ArraySeq::fold()
     * @see \ScalikePHP\TraversableSeq::fold()
     */
    public function testFold(): void
    {
        $seq = $this->seq("Fizz", "Buzz", "FizzBuzz");
        $f = function (string $z, string $x): string {
            return $z . $x;
        };
        $g = function (string $z, string $x): string {
            return $x . $z;
        };
        Assert::same("FizzBuzzFizzBuzz", $seq->fold("", $f));
        Assert::same("FizzBuzzBuzzFizz", $seq->fold("", $g));
    }

    /**
     * Tests for Seq::forAll().
     *
     * @see \ScalikePHP\ArraySeq::forAll()
     * @see \ScalikePHP\TraversableSeq::forAll()
     */
    public function testForAll(): void
    {
        $seq = $this->seq("Foo", "Bar", "Baz");
        $f = function (string $x): bool {
            return strlen($x) === 3;
        };
        $g = function (string $x): bool {
            return preg_match('/\ABa[rz]\z/', $x) !== 0;
        };
        Assert::true($seq->forAll($f));
        Assert::false($seq->forAll($g));
    }

    /**
     * Tests for Seq::groupBy().
     *
     * @see \ScalikePHP\ArraySeq::groupBy()
     * @see \ScalikePHP\TraversableSeq::groupBy()
     */
    public function testGroupBy(): void
    {
        $seq = $this->seq(
            ["name" => "php", "type" => "language"],
            ["name" => "python", "type" => "language"],
            ["name" => "scala", "type" => "language"],
            ["name" => "symfony", "type" => "framework"],
            ["name" => "django", "type" => "framework"],
            ["name" => "playframework", "type" => "framework"]
        );
        $f = function (array $item): string {
            return $item["type"];
        };
        $g = function (Seq $items): array {
            return $items
                ->map(function (array $item): string {
                    return $item["name"];
                })
                ->toArray();
        };
        $expected = [
            "language" => ["php", "python", "scala"],
            "framework" => ["symfony", "django", "playframework"]
        ];
        Assert::same($expected, $seq->groupBy($f)->mapValues($g)->toAssoc());
        Assert::same($expected, $seq->groupBy("type")->mapValues($g)->toAssoc());
    }

    /**
     * Tests for Seq::head().
     *
     * @see \ScalikePHP\ArraySeq::head()
     * @see \ScalikePHP\TraversableSeq::head()
     */
    public function testHead(): void
    {
        Assert::same("foo", $this->seq("foo", "bar", "baz")->head());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->seq()->head();
            }
        );
    }

    /**
     * Tests for Seq::headOption().
     *
     * @see \ScalikePHP\ArraySeq::headOption()
     * @see \ScalikePHP\TraversableSeq::headOption()
     */
    public function testHeadOption(): void
    {
        Assert::some("foo", $this->seq("foo", "bar", "baz")->headOption());
        Assert::none($this->seq()->headOption());
    }

    /**
     * Tests for Seq::isEmpty().
     *
     * @see \ScalikePHP\ArraySeq::isEmpty()
     * @see \ScalikePHP\TraversableSeq::isEmpty()
     */
    public function testIsEmpty(): void
    {
        Assert::true($this->seq()->isEmpty());
        Assert::false($this->seq("foo")->isEmpty());
    }

    /**
     * Tests for Seq::jsonSerialize().
     *
     * @see \ScalikePHP\ArraySeq::jsonSerialize()
     * @see \ScalikePHP\TraversableSeq::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        Assert::same(
            json_encode(["foo", "bar", "baz"]),
            json_encode($this->seq("foo", "bar", "baz"))
        );
    }

    /**
     * Tests for Seq::last().
     *
     * @see \ScalikePHP\ArraySeq::last()
     * @see \ScalikePHP\TraversableSeq::last()
     */
    public function testLast(): void
    {
        Assert::same("baz", $this->seq("foo", "bar", "baz")->last());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->seq()->last();
            }
        );
    }

    /**
     * Tests for Seq::lastOption().
     *
     * @see \ScalikePHP\ArraySeq::lastOption()
     * @see \ScalikePHP\TraversableSeq::lastOption()
     */
    public function testLastOption(): void
    {
        Assert::some("baz", $this->seq("foo", "bar", "baz")->lastOption());
        Assert::none($this->seq()->lastOption());
    }

    /**
     * Tests for Seq::map().
     *
     * @see \ScalikePHP\ArraySeq::map()
     * @see \ScalikePHP\TraversableSeq::map()
     */
    public function testMap(): void
    {
        $seq = $this->seq("Fizz", "Buzz", "FizzBuzz");
        $f = function (string $x): string {
            return strtoupper($x);
        };
        $g = function (string $x): int {
            return strlen($x);
        };
        Assert::same(["FIZZ", "BUZZ", "FIZZBUZZ"], $seq->map($f)->toArray());
        Assert::same([4, 4, 8], $seq->map($g)->toArray());
    }

    /**
     * Tests for Seq::max().
     *
     * @see \ScalikePHP\ArraySeq::max()
     * @see \ScalikePHP\TraversableSeq::max()
     */
    public function testMax(): void
    {
        Assert::same(9, $this->seq(1, 9, 2, 8, 3, 7, 4, 6, 5, 0)->max());
        Assert::same("Z", $this->seq("A", "Z", "B", "Y", "C", "X")->max());
    }

    /**
     * Tests for Seq::maxBy().
     *
     * @see \ScalikePHP\ArraySeq::maxBy()
     * @see \ScalikePHP\TraversableSeq::maxBy()
     */
    public function testMaxBy(): void
    {
        $seq = $this->seq("alpaca", "zebra", "buffalo", "yak", "camel", "wolf", "dog", "viper", "eagle");
        $f = function (string $x): int {
            return strlen($x);
        };
        $g = function (string $x): string {
            return substr($x, 0, 1);
        };
        Assert::same("buffalo", $seq->maxBy($f));
        Assert::same("zebra", $seq->maxBy($g));
    }

    /**
     * Tests for Seq::min().
     *
     * @see \ScalikePHP\ArraySeq::min()
     * @see \ScalikePHP\TraversableSeq::min()
     */
    public function testMin(): void
    {
        Assert::same(0, $this->seq(1, 9, 2, 8, 3, 7, 4, 6, 5, 0)->min());
        Assert::same("A", $this->seq("A", "Z", "B", "Y", "C", "X")->min());
    }

    /**
     * Tests for Seq::minBy().
     *
     * @see \ScalikePHP\ArraySeq::minBy()
     * @see \ScalikePHP\TraversableSeq::minBy()
     */
    public function testMinBy(): void
    {
        $seq = $this->seq("alpaca", "zebra", "buffalo", "yak", "camel", "wolf", "dog", "viper", "eagle");
        $f = function (string $x): int {
            return strlen($x);
        };
        $g = function (string $x): string {
            return substr($x, 0, 1);
        };
        Assert::same("yak", $seq->minBy($f));
        Assert::same("alpaca", $seq->minBy($g));
    }

    /**
     * Tests for Seq::mkString().
     *
     * @see \ScalikePHP\ArraySeq::mkString()
     * @see \ScalikePHP\TraversableSeq::mkString()
     */
    public function testMkString(): void
    {
        $seq = $this->seq("Fizz", "Buzz", "FizzBuzz");
        Assert::same("FizzBuzzFizzBuzz", $seq->mkString());
        Assert::same("Fizz,Buzz,FizzBuzz", $seq->mkString(","));
        Assert::same("Fizz, Buzz, FizzBuzz", $seq->mkString(", "));
    }

    /**
     * Tests for Seq::nonEmpty().
     *
     * @see \ScalikePHP\ArraySeq::nonEmpty()
     * @see \ScalikePHP\TraversableSeq::nonEmpty()
     */
    public function testNonEmpty(): void
    {
        Assert::true($this->seq("foo")->nonEmpty());
        Assert::false($this->seq()->nonEmpty());
    }

    /**
     * Tests for Seq::offsetExists().
     *
     * @see \ScalikePHP\ArraySeq::offsetExists()
     * @see \ScalikePHP\TraversableSeq::offsetExists()
     */
    public function testOffsetExists(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        Assert::true(isset($seq[0]));
        Assert::true(isset($seq[1]));
        Assert::true(isset($seq[2]));
        Assert::false(isset($seq[3]));
        Assert::false(isset($seq[-1]));
    }

    /**
     * Tests for Seq::offsetGet().
     *
     * @see \ScalikePHP\ArraySeq::offsetGet()
     * @see \ScalikePHP\TraversableSeq::offsetGet()
     */
    public function testOffsetGet(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        Assert::same("foo", $seq[0]);
        Assert::same("bar", $seq[1]);
        Assert::same("baz", $seq[2]);
        Assert::same("baz", $seq[2]);
        Assert::same("bar", $seq[1]);
        Assert::same("foo", $seq[0]);
    }

    /**
     * Tests for Seq::offsetSet().
     *
     * @see \ScalikePHP\ArraySeq::offsetSet()
     * @see \ScalikePHP\TraversableSeq::offsetSet()
     */
    public function testOffsetSet(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function () use ($seq): void {
            $seq[0] = "FOO";
        };
        $g = function () use ($seq): void {
            $seq[3] = "FizzBuzz";
        };
        Assert::throws(\BadMethodCallException::class, $f);
        Assert::throws(\BadMethodCallException::class, $g);
    }

    /**
     * Tests for Seq::offsetUnset().
     *
     * @see \ScalikePHP\ArraySeq::offsetUnset()
     * @see \ScalikePHP\TraversableSeq::offsetUnset()
     */
    public function testOffsetUnset(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        $f = function () use ($seq): void {
            unset($seq[0]);
        };
        $g = function () use ($seq): void {
            unset($seq[3]);
        };
        Assert::throws(\BadMethodCallException::class, $f);
        Assert::throws(\BadMethodCallException::class, $g);
    }

    /**
     * Tests for Seq::prepend().
     *
     * @see \ScalikePHP\ArraySeq::prepend()
     * @see \ScalikePHP\TraversableSeq::prepend()
     */
    public function testPrepend(): void
    {
        $seq = $this->seq("baz");
        Assert::same(["baz"], $seq->toArray());
        Assert::same(["bar", "baz"], $seq->prepend(["bar"])->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->prepend(["foo", "bar"])->toArray());
    }

    /**
     * Tests for Seq::reverse().
     *
     * @see \ScalikePHP\ArraySeq::reverse()
     * @see \ScalikePHP\TraversableSeq::reverse()
     */
    public function testReverse(): void
    {
        Assert::same(["baz", "bar", "foo"], $this->seq("foo", "bar", "baz")->reverse()->toArray());
        Assert::same(["FizzBuzz", "Buzz", "Fizz"], $this->seq("Fizz", "Buzz", "FizzBuzz")->reverse()->toArray());
    }

    /**
     * Tests for Seq::size().
     *
     * @see \ScalikePHP\ArraySeq::size()
     * @see \ScalikePHP\TraversableSeq::size()
     */
    public function testSize(): void
    {
        Assert::same(0, ($this->seq())->size());
        Assert::same(1, ($this->seq("foo"))->size());
        Assert::same(2, ($this->seq("foo", "bar"))->size());
        Assert::same(3, ($this->seq("foo", "bar", "baz"))->size());
    }
    /**
     * Tests for Seq::sum().
     *
     * @see \ScalikePHP\ArraySeq::sum()
     * @see \ScalikePHP\TraversableSeq::sum()
     */
    public function testSum(): void
    {
        Assert::same(0, $this->seq()->sum());
        Assert::same(0, $this->seq("a", "b", "c")->sum());
        Assert::same(10, $this->seq(1, 2, 3, 4)->sum());
    }

    /**
     * Tests for Seq::sumBy().
     *
     * @see \ScalikePHP\ArraySeq::sumBy()
     * @see \ScalikePHP\TraversableSeq::sumBy()
     */
    public function testSumBy(): void
    {
        $f = function(int $z, string $value): int {
            return $z + strlen($value);
        };
        Assert::same(0, $this->seq()->sumBy($f));
        Assert::same(10, $this->seq("a", "pi", "dog", "beer")->sumBy($f));
    }

    /**
     * Tests for Seq::sortBy().
     *
     * @see \ScalikePHP\ArraySeq::sortBy()
     * @see \ScalikePHP\TraversableSeq::sortBy()
     */
    public function testSortBy(): void
    {
        $seq = $this->seq(
            ["name" => "Carol"],
            ["name" => "Alice"],
            ["name" => "Frank"],
            ["name" => "Bob"],
            ["name" => "Ellen"]
        );
        $f = function (array $item): string {
            return $item["name"];
        };
        Assert::same(["Alice", "Bob", "Carol", "Ellen", "Frank"], $seq->sortBy($f)->map($f)->toArray());
        Assert::same(["Alice", "Bob", "Carol", "Ellen", "Frank"], $seq->sortBy("name")->map($f)->toArray());
    }

    /**
     * Tests for Seq::take().
     *
     * @see \ScalikePHP\ArraySeq::take()
     * @see \ScalikePHP\TraversableSeq::take()
     */
    public function testTake(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        Assert::same([], $seq->take(0)->toArray());
        Assert::same(["foo"], $seq->take(1)->toArray());
        Assert::same(["foo", "bar"], $seq->take(2)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->take(3)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->take(4)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->take(100)->toArray());
    }

    /**
     * Tests for Seq::takeRight().
     *
     * @see \ScalikePHP\ArraySeq::takeRight()
     * @see \ScalikePHP\TraversableSeq::takeRight()
     */
    public function testTakeRight(): void
    {
        $seq = $this->seq("foo", "bar", "baz");
        Assert::same([], $seq->takeRight(0)->toArray());
        Assert::same(["baz"], $seq->takeRight(1)->toArray());
        Assert::same(["bar", "baz"], $seq->takeRight(2)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->takeRight(3)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->takeRight(4)->toArray());
        Assert::same(["foo", "bar", "baz"], $seq->takeRight(100)->toArray());
    }

    /**
     * Tests for Seq::toArray().
     *
     * @see \ScalikePHP\ArraySeq::toArray()
     * @see \ScalikePHP\TraversableSeq::toArray()
     */
    public function testToArray(): void
    {
        Assert::same([], $this->seq()->toArray());
        Assert::same(["foo", "bar", "baz"], $this->seq("foo", "bar", "baz")->toArray());
        Assert::same(["Alice", "Bob", "Carol"], $this->seq("Alice", "Bob", "Carol")->toArray());
    }

    /**
     * Tests for Seq::toMap().
     *
     * @see \ScalikePHP\ArraySeq::toMap()
     * @see \ScalikePHP\TraversableSeq::toMap()
     */
    public function testToMap(): void
    {
        $seq = $this->seq(
            ["name" => "php", "type" => "language"],
            ["name" => "python", "type" => "language"],
            ["name" => "scala", "type" => "language"],
            ["name" => "symfony", "type" => "framework"],
            ["name" => "django", "type" => "framework"],
            ["name" => "playframework", "type" => "framework"]
        );
        $f = function (array $item): string {
            return $item["name"];
        };
        $expected = [
            "php" => ["name" => "php", "type" => "language"],
            "python" => ["name" => "python", "type" => "language"],
            "scala" => ["name" => "scala", "type" => "language"],
            "symfony" => ["name" => "symfony", "type" => "framework"],
            "django" => ["name" => "django", "type" => "framework"],
            "playframework" => ["name" => "playframework", "type" => "framework"],
        ];
        Assert::same($expected, $seq->toMap($f)->toAssoc());
        Assert::same($expected, $seq->toMap("name")->toAssoc());
    }

    /**
     * Tests for Seq::toSeq().
     *
     * @see \ScalikePHP\ArraySeq::toSeq()
     * @see \ScalikePHP\TraversableSeq::toSeq()
     */
    public function testToSeq(): void
    {
        $a = $this->seq();
        $b = $this->seq("foo", "bar", "baz");
        $c = $this->seq("Alice", "Bob", "Carol", "Ellen");
        Assert::same($a, $a->toSeq());
        Assert::same($b, $b->toSeq());
        Assert::same($c, $c->toSeq());
    }

}
