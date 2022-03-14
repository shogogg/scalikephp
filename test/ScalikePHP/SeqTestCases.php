<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\Seq;

/**
 * Tests for Seqs.
 */
trait SeqTestCases
{
    /**
     * Create a Seq for testing.
     *
     * @param array $values
     *
     * @return \ScalikePHP\Seq
     */
    abstract protected function seq(...$values): Seq;

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::append()
     * @covers \ScalikePHP\Implementations\TraversableSeq::append()
     * @noinspection PhpUnused
     */
    public function testAppend(): void
    {
        $seq = $this->seq('foo');
        Assert::same(['foo'], $seq->toArray());
        Assert::same(['foo', 'bar'], $seq->append(['bar'])->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->append(['bar', 'baz'])->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::contains()
     * @covers \ScalikePHP\Implementations\TraversableSeq::contains()
     * @noinspection PhpUnused
     */
    public function testContains(): void
    {
        $seq = $this->seq('foo', 'bar');
        Assert::true($seq->contains('foo'));
        Assert::true($seq->contains('bar'));
        Assert::false($seq->contains('baz'));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::count()
     * @covers \ScalikePHP\Implementations\TraversableSeq::count()
     * @noinspection PhpUnused
     */
    public function testCount(): void
    {
        Assert::same(0, ($this->seq())->count());
        Assert::same(1, ($this->seq('foo'))->count());
        Assert::same(2, ($this->seq('foo', 'bar'))->count());
        Assert::same(3, ($this->seq('foo', 'bar', 'baz'))->count());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::distinct()
     * @covers \ScalikePHP\Implementations\TraversableSeq::distinct()
     * @noinspection PhpUnused
     */
    public function testDistinct(): void
    {
        $seq = $this->seq('foo', 'bar', 'foo', 'baz', 'bar', 'foo', 'baz');
        Assert::same(['foo', 'bar', 'baz'], $seq->distinct()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::distinctBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::distinctBy()
     * @noinspection PhpUnused
     */
    public function testDistinctBy(): void
    {
        $seq = $this->seq(
            ['name' => 'foo'],
            ['name' => 'bar'],
            ['name' => 'foo'],
            ['name' => 'baz'],
            ['name' => 'bar'],
            ['name' => 'foo'],
            ['name' => 'baz']
        );
        $f = fn (array $x) => $x['name'];
        Assert::same(
            [['name' => 'foo'], ['name' => 'bar'], ['name' => 'baz']],
            $seq->distinctBy($f)->toArray()
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::drop()
     * @covers \ScalikePHP\Implementations\TraversableSeq::drop()
     * @noinspection PhpUnused
     */
    public function testDrop(): void
    {
        $seq = $this->seq('one', 'two', 'three', 'four', 'five');
        Assert::instanceOf(Seq::class, $seq->drop(3));
        Assert::same(['four', 'five'], $seq->drop(3)->toArray());
        Assert::instanceOf(Seq::class, $seq->drop(2));
        Assert::same(['three', 'four', 'five'], $seq->drop(2)->toArray());
        Assert::instanceOf(Seq::class, $seq->drop(1));
        Assert::same(['two', 'three', 'four', 'five'], $seq->drop(1)->toArray());
        Assert::instanceOf(Seq::class, $seq->drop(0));
        Assert::same(['one', 'two', 'three', 'four', 'five'], $seq->drop(0)->toArray());
        Assert::instanceOf(Seq::class, $seq->drop(-1));
        Assert::same(['one', 'two', 'three', 'four', 'five'], $seq->drop(-1)->toArray());
        Assert::instanceOf(Seq::class, $seq->drop(-2));
        Assert::same(['one', 'two', 'three', 'four', 'five'], $seq->drop(-2)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::each()
     * @covers \ScalikePHP\Implementations\TraversableSeq::each()
     * @noinspection PhpUnused
     */
    public function testEach(): void
    {
        $spy = self::spy();
        $f = function () use ($spy): void {
            call_user_func_array([$spy, 'spy'], func_get_args());
        };
        $spy->shouldReceive('spy')->with(1, 0)->once();
        $spy->shouldReceive('spy')->with(2, 1)->once();
        $spy->shouldReceive('spy')->with(3, 2)->once();
        $this->seq(1, 2, 3)->each($f);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::exists()
     * @covers \ScalikePHP\Implementations\TraversableSeq::exists()
     * @noinspection PhpUnused
     */
    public function testExists(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $f = fn (string $x): bool => strlen($x) === 3;
        $g = fn (string $x): bool => strlen($x) === 4;
        Assert::true($seq->exists($f));
        Assert::false($seq->exists($g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::filter()
     * @covers \ScalikePHP\Implementations\TraversableSeq::filter()
     * @noinspection PhpUnused
     */
    public function testFilter(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $f = fn (string $x): bool => $x !== 'foo';
        $g = fn (string $x): bool => $x !== 'baz';
        Assert::same(['bar', 'baz'], $seq->filter($f)->toArray());
        Assert::same(['foo', 'bar'], $seq->filter($g)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::filterNot()
     * @covers \ScalikePHP\Implementations\TraversableSeq::filterNot()
     * @noinspection PhpUnused
     */
    public function testFilterNot(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $f = fn (string $x): bool => $x === 'foo';
        $g = fn (string $x): bool => $x === 'baz';
        Assert::same(['bar', 'baz'], $seq->filterNot($f)->toArray());
        Assert::same(['foo', 'bar'], $seq->filterNot($g)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::find()
     * @covers \ScalikePHP\Implementations\TraversableSeq::find()
     * @noinspection PhpUnused
     */
    public function testFind(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $f = fn (string $x): bool => $x !== 'foo';
        $g = fn (string $x): bool => $x === 'FizzBuzz';
        Assert::some('bar', $seq->find($f));
        Assert::none($seq->find($g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::flatMap()
     * @covers \ScalikePHP\Implementations\TraversableSeq::flatMap()
     * @noinspection PhpUnused
     */
    public function testFlatMap(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');

        $f = fn (string $x): array => [$x, strtoupper($x)];
        Assert::same(['foo', 'FOO', 'bar', 'BAR', 'baz', 'BAZ'], $seq->flatMap($f)->toArray());

        $g = fn (string $x): Seq => $this->seq($x, ucfirst($x));
        Assert::same(['foo', 'Foo', 'bar', 'Bar', 'baz', 'Baz'], $seq->flatMap($g)->toArray());

        $spy = self::spy();
        $h = function () use ($spy): array {
            call_user_func_array([$spy, 'spy'], func_get_args());
            return func_get_args();
        };
        $spy->shouldReceive('spy')->with('foo', 0)->once();
        $spy->shouldReceive('spy')->with('bar', 1)->once();
        $spy->shouldReceive('spy')->with('baz', 2)->once();
        $seq->flatMap($h)->computed();
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::flatten()
     * @covers \ScalikePHP\Implementations\TraversableSeq::flatten()
     * @noinspection PhpUnused
     */
    public function testFlatten(): void
    {
        $seq = $this->seq(['foo', 'bar', 'baz'], ['Fizz', 'Buzz', 'FizzBuzz']);
        Assert::same(['foo', 'bar', 'baz', 'Fizz', 'Buzz', 'FizzBuzz'], $seq->flatten()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::fold()
     * @covers \ScalikePHP\Implementations\TraversableSeq::fold()
     * @noinspection PhpUnused
     */
    public function testFold(): void
    {
        $seq = $this->seq('Fizz', 'Buzz', 'FizzBuzz');
        $f = fn (string $z, string $x): string => $z . $x;
        $g = fn (string $z, string $x): string => $x . $z;
        Assert::same('FizzBuzzFizzBuzz', $seq->fold('', $f));
        Assert::same('FizzBuzzBuzzFizz', $seq->fold('', $g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::forAll()
     * @covers \ScalikePHP\Implementations\TraversableSeq::forAll()
     * @noinspection PhpUnused
     */
    public function testForAll(): void
    {
        $seq = $this->seq('Foo', 'Bar', 'Baz');
        $f = fn (string $x): bool => strlen($x) === 3;
        $g = fn (string $x): bool => preg_match('/\ABa[rz]\z/', $x) !== 0;
        Assert::true($seq->forAll($f));
        Assert::false($seq->forAll($g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::groupBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::groupBy()
     * @noinspection PhpUnused
     */
    public function testGroupBy(): void
    {
        $seq = $this->seq(
            ['name' => 'php', 'type' => 'language'],
            ['name' => 'python', 'type' => 'language'],
            ['name' => 'scala', 'type' => 'language'],
            ['name' => 'symfony', 'type' => 'framework'],
            ['name' => 'django', 'type' => 'framework'],
            ['name' => 'playframework', 'type' => 'framework']
        );
        $f = fn (array $item): string => $item['type'];
        $g = fn (Seq $items): array => $items->map(fn (array $item): string => $item['name'])->toArray();
        $expected = [
            'language' => ['php', 'python', 'scala'],
            'framework' => ['symfony', 'django', 'playframework'],
        ];
        Assert::same($expected, $seq->groupBy($f)->mapValues($g)->toAssoc());
        Assert::same($expected, $seq->groupBy('type')->mapValues($g)->toAssoc());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::head()
     * @covers \ScalikePHP\Implementations\TraversableSeq::head()
     * @noinspection PhpUnused
     */
    public function testHead(): void
    {
        Assert::same('foo', $this->seq('foo', 'bar', 'baz')->head());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->seq()->head();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::headOption()
     * @covers \ScalikePHP\Implementations\TraversableSeq::headOption()
     * @noinspection PhpUnused
     */
    public function testHeadOption(): void
    {
        Assert::some('foo', $this->seq('foo', 'bar', 'baz')->headOption());
        Assert::none($this->seq()->headOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::indexOf()
     * @covers \ScalikePHP\Implementations\TraversableSeq::indexOf()
     * @noinspection PhpUnused
     */
    public function testIndexOf(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::same(0, $seq->indexOf('foo'));
        Assert::same(2, $seq->indexOf('baz'));
        Assert::same(1, $seq->indexOf('bar'));
        Assert::same(-1, $seq->indexOf('qux'));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::isEmpty()
     * @covers \ScalikePHP\Implementations\TraversableSeq::isEmpty()
     * @noinspection PhpUnused
     */
    public function testIsEmpty(): void
    {
        Assert::true($this->seq()->isEmpty());
        Assert::false($this->seq('foo')->isEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::jsonSerialize()
     * @covers \ScalikePHP\Implementations\TraversableSeq::jsonSerialize()
     * @noinspection PhpUnused
     */
    public function testJsonSerialize(): void
    {
        Assert::same(
            json_encode(['foo', 'bar', 'baz']),
            json_encode($this->seq('foo', 'bar', 'baz'))
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::last()
     * @covers \ScalikePHP\Implementations\TraversableSeq::last()
     * @noinspection PhpUnused
     */
    public function testLast(): void
    {
        Assert::same('baz', $this->seq('foo', 'bar', 'baz')->last());
        Assert::throws(
            \LogicException::class,
            function (): void {
                $this->seq()->last();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::lastOption()
     * @covers \ScalikePHP\Implementations\TraversableSeq::lastOption()
     * @noinspection PhpUnused
     */
    public function testLastOption(): void
    {
        Assert::some('baz', $this->seq('foo', 'bar', 'baz')->lastOption());
        Assert::none($this->seq()->lastOption());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::map()
     * @covers \ScalikePHP\Implementations\TraversableSeq::map()
     * @noinspection PhpUnused
     */
    public function testMap(): void
    {
        $seq = $this->seq('Fizz', 'Buzz', 'FizzBuzz');

        $f = fn (string $x): string => strtoupper($x);
        Assert::same(['FIZZ', 'BUZZ', 'FIZZBUZZ'], $seq->map($f)->toArray());

        $g = fn (string $x): int => strlen($x);
        Assert::same([4, 4, 8], $seq->map($g)->toArray());

        $spy = self::spy();
        $h = function () use ($spy): array {
            call_user_func_array([$spy, 'spy'], func_get_args());
            return func_get_args();
        };
        $spy->shouldReceive('spy')->with('Fizz', 0)->once();
        $spy->shouldReceive('spy')->with('Buzz', 1)->once();
        $spy->shouldReceive('spy')->with('FizzBuzz', 2)->once();
        $seq->map($h)->computed();
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::max()
     * @covers \ScalikePHP\Implementations\TraversableSeq::max()
     * @noinspection PhpUnused
     */
    public function testMax(): void
    {
        Assert::same(9, $this->seq(1, 9, 2, 8, 3, 7, 4, 6, 5, 0)->max());
        Assert::same('Z', $this->seq('A', 'Z', 'B', 'Y', 'C', 'X')->max());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::maxBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::maxBy()
     * @noinspection PhpUnused
     */
    public function testMaxBy(): void
    {
        $seq = $this->seq('alpaca', 'zebra', 'buffalo', 'yak', 'camel', 'wolf', 'dog', 'viper', 'eagle');
        $f = fn (string $x): int => strlen($x);
        $g = fn (string $x): string => substr($x, 0, 1);
        Assert::same('buffalo', $seq->maxBy($f));
        Assert::same('zebra', $seq->maxBy($g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::min()
     * @covers \ScalikePHP\Implementations\TraversableSeq::min()
     * @noinspection PhpUnused
     */
    public function testMin(): void
    {
        Assert::same(0, $this->seq(1, 9, 2, 8, 3, 7, 4, 6, 5, 0)->min());
        Assert::same('A', $this->seq('A', 'Z', 'B', 'Y', 'C', 'X')->min());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::minBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::minBy()
     * @noinspection PhpUnused
     */
    public function testMinBy(): void
    {
        $seq = $this->seq('alpaca', 'zebra', 'buffalo', 'yak', 'camel', 'wolf', 'dog', 'viper', 'eagle');
        $f = fn (string $x): int => strlen($x);
        $g = fn (string $x): string => substr($x, 0, 1);
        Assert::same('yak', $seq->minBy($f));
        Assert::same('alpaca', $seq->minBy($g));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::mkString()
     * @covers \ScalikePHP\Implementations\TraversableSeq::mkString()
     * @noinspection PhpUnused
     */
    public function testMkString(): void
    {
        $seq = $this->seq('Fizz', 'Buzz', 'FizzBuzz');
        Assert::same('FizzBuzzFizzBuzz', $seq->mkString());
        Assert::same('Fizz,Buzz,FizzBuzz', $seq->mkString(','));
        Assert::same('Fizz, Buzz, FizzBuzz', $seq->mkString(', '));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::nonEmpty()
     * @covers \ScalikePHP\Implementations\TraversableSeq::nonEmpty()
     * @noinspection PhpUnused
     */
    public function testNonEmpty(): void
    {
        Assert::true($this->seq('foo')->nonEmpty());
        Assert::false($this->seq()->nonEmpty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::offsetExists()
     * @covers \ScalikePHP\Implementations\TraversableSeq::offsetExists()
     * @noinspection PhpUnused
     */
    public function testOffsetExists(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::true(isset($seq[0]));
        Assert::true(isset($seq[1]));
        Assert::true(isset($seq[2]));
        Assert::false(isset($seq[3]));
        Assert::false(isset($seq[-1]));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::offsetGet()
     * @covers \ScalikePHP\Implementations\TraversableSeq::offsetGet()
     * @noinspection PhpUnused
     */
    public function testOffsetGet(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::same('foo', $seq[0]);
        Assert::same('bar', $seq[1]);
        Assert::same('baz', $seq[2]);
        Assert::same('baz', $seq[2]);
        Assert::same('bar', $seq[1]);
        Assert::same('foo', $seq[0]);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::offsetSet()
     * @covers \ScalikePHP\Implementations\TraversableSeq::offsetSet()
     * @noinspection PhpUnused
     */
    public function testOffsetSet(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $f = function () use ($seq): void {
            $seq[0] = 'FOO';
        };
        $g = function () use ($seq): void {
            $seq[3] = 'FizzBuzz';
        };
        Assert::throws(\BadMethodCallException::class, $f);
        Assert::throws(\BadMethodCallException::class, $g);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::offsetUnset()
     * @covers \ScalikePHP\Implementations\TraversableSeq::offsetUnset()
     * @noinspection PhpUnused
     */
    public function testOffsetUnset(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
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
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::partition()
     * @covers \ScalikePHP\Implementations\TraversableSeq::partition()
     * @noinspection PhpUnused
     */
    public function testPartition(): void
    {
        $seq = $this->seq(1, 2, 3, 4, 5, 6, 7, 8);
        $a = $seq->partition(fn (int $x): bool => $x % 2 === 0);
        $b = $seq->partition(fn (int $x): bool => $x % 2 !== 0);

        Assert::true(is_array($a));
        Assert::same(2, count($a));
        Assert::instanceOf(Seq::class, $a[0]);
        Assert::instanceOf(Seq::class, $a[1]);
        Assert::same([2, 4, 6, 8], $a[0]->toArray());
        Assert::same([1, 3, 5, 7], $a[1]->toArray());

        Assert::true(is_array($b));
        Assert::same(2, count($b));
        Assert::instanceOf(Seq::class, $b[0]);
        Assert::instanceOf(Seq::class, $b[1]);
        Assert::same([1, 3, 5, 7], $b[0]->toArray());
        Assert::same([2, 4, 6, 8], $b[1]->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::prepend()
     * @covers \ScalikePHP\Implementations\TraversableSeq::prepend()
     * @noinspection PhpUnused
     */
    public function testPrepend(): void
    {
        $seq = $this->seq('baz');
        Assert::same(['baz'], $seq->toArray());
        Assert::same(['bar', 'baz'], $seq->prepend(['bar'])->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->prepend(['foo', 'bar'])->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::reverse()
     * @covers \ScalikePHP\Implementations\TraversableSeq::reverse()
     * @noinspection PhpUnused
     */
    public function testReverse(): void
    {
        Assert::same(['baz', 'bar', 'foo'], $this->seq('foo', 'bar', 'baz')->reverse()->toArray());
        Assert::same(['FizzBuzz', 'Buzz', 'Fizz'], $this->seq('Fizz', 'Buzz', 'FizzBuzz')->reverse()->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::size()
     * @covers \ScalikePHP\Implementations\TraversableSeq::size()
     * @noinspection PhpUnused
     */
    public function testSize(): void
    {
        Assert::same(0, ($this->seq())->size());
        Assert::same(1, ($this->seq('foo'))->size());
        Assert::same(2, ($this->seq('foo', 'bar'))->size());
        Assert::same(3, ($this->seq('foo', 'bar', 'baz'))->size());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::sortBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::sortBy()
     * @noinspection PhpUnused
     */
    public function testSortBy(): void
    {
        $seq = $this->seq(
            ['name' => 'Carol'],
            ['name' => 'Alice'],
            ['name' => 'Frank'],
            ['name' => 'Bob'],
            ['name' => 'Ellen']
        );
        $f = fn (array $item): string => $item['name'];
        Assert::same(['Alice', 'Bob', 'Carol', 'Ellen', 'Frank'], $seq->sortBy($f)->map($f)->toArray());
        Assert::same(['Alice', 'Bob', 'Carol', 'Ellen', 'Frank'], $seq->sortBy('name')->map($f)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::sum()
     * @covers \ScalikePHP\Implementations\TraversableSeq::sum()
     * @noinspection PhpUnused
     */
    public function testSum(): void
    {
        Assert::same(0, $this->seq()->sum());
        Assert::same(0, $this->seq('a', 'b', 'c')->sum());
        Assert::same(10, $this->seq(1, 2, 3, 4)->sum());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::sumBy()
     * @covers \ScalikePHP\Implementations\TraversableSeq::sumBy()
     * @noinspection PhpUnused
     */
    public function testSumBy(): void
    {
        $f = fn (int $z, string $value): int => $z + strlen($value);
        Assert::same(0, $this->seq()->sumBy($f));
        Assert::same(10, $this->seq('a', 'pi', 'dog', 'beer')->sumBy($f));
    }

    /**
     * @test
     * @covers \ScalikePHP\Seq::tail()
     * @noinspection PhpUnused
     */
    public function testTail(): void
    {
        $seq = $this->seq('one', 'two', 'three', 'four', 'five');
        Assert::instanceOf(Seq::class, $seq->tail());
        Assert::same(['two', 'three', 'four', 'five'], $seq->tail()->toArray());
        Assert::same(['three', 'four', 'five'], $seq->tail()->tail()->toArray());
        Assert::throws(
            \LogicException::class,
            function (): void {
                Seq::empty()->tail();
            }
        );
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::take()
     * @covers \ScalikePHP\Implementations\TraversableSeq::take()
     * @noinspection PhpUnused
     */
    public function testTake(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::same([], $seq->take(0)->toArray());
        Assert::same(['foo'], $seq->take(1)->toArray());
        Assert::same(['foo', 'bar'], $seq->take(2)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->take(3)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->take(4)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->take(100)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::takeRight()
     * @covers \ScalikePHP\Implementations\TraversableSeq::takeRight()
     * @noinspection PhpUnused
     */
    public function testTakeRight(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::same([], $seq->takeRight(0)->toArray());
        Assert::same(['baz'], $seq->takeRight(1)->toArray());
        Assert::same(['bar', 'baz'], $seq->takeRight(2)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->takeRight(3)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->takeRight(4)->toArray());
        Assert::same(['foo', 'bar', 'baz'], $seq->takeRight(100)->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::toArray()
     * @covers \ScalikePHP\Implementations\TraversableSeq::toArray()
     * @noinspection PhpUnused
     */
    public function testToArray(): void
    {
        Assert::same([], $this->seq()->toArray());
        Assert::same(['foo', 'bar', 'baz'], $this->seq('foo', 'bar', 'baz')->toArray());
        Assert::same(['Alice', 'Bob', 'Carol'], $this->seq('Alice', 'Bob', 'Carol')->toArray());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::toMap()
     * @covers \ScalikePHP\Implementations\TraversableSeq::toMap()
     * @noinspection PhpUnused
     */
    public function testToMap(): void
    {
        $seq = $this->seq(
            ['name' => 'php', 'type' => 'language'],
            ['name' => 'python', 'type' => 'language'],
            ['name' => 'scala', 'type' => 'language'],
            ['name' => 'symfony', 'type' => 'framework'],
            ['name' => 'django', 'type' => 'framework'],
            ['name' => 'playframework', 'type' => 'framework']
        );
        $f = fn (array $item): string => $item['name'];
        $expected = [
            'php' => ['name' => 'php', 'type' => 'language'],
            'python' => ['name' => 'python', 'type' => 'language'],
            'scala' => ['name' => 'scala', 'type' => 'language'],
            'symfony' => ['name' => 'symfony', 'type' => 'framework'],
            'django' => ['name' => 'django', 'type' => 'framework'],
            'playframework' => ['name' => 'playframework', 'type' => 'framework'],
        ];
        Assert::same($expected, $seq->toMap($f)->toAssoc());
        Assert::same($expected, $seq->toMap('name')->toAssoc());
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::toSeq()
     * @covers \ScalikePHP\Implementations\TraversableSeq::toSeq()
     * @noinspection PhpUnused
     */
    public function testToSeq(): void
    {
        $a = $this->seq();
        $b = $this->seq('foo', 'bar', 'baz');
        $c = $this->seq('Alice', 'Bob', 'Carol', 'Ellen');
        Assert::same($a, $a->toSeq());
        Assert::same($b, $b->toSeq());
        Assert::same($c, $c->toSeq());
    }
}
