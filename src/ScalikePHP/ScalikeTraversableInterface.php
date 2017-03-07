<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

/**
 * Scala like Traversable Interface.
 */
interface ScalikeTraversableInterface extends \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{

    /**
     * 値の全要素に対して関数 $f を適用する.
     *
     * @param \Closure $f
     * @return void
     */
    public function each(\Closure $f): void;

    /**
     * 条件にマッチする（関数が true を返す）要素の有無を判定する.
     *
     * @param \Closure $p 真偽値を返す関数
     * @return bool 条件を満たす要素がある場合に true, そうでない場合は false
     */
    public function exists(\Closure $p): bool;

    /**
     * 条件にマッチする（関数が true を返す）要素のみを抽出する.
     *
     * @param \Closure $p 真偽値を返す関数
     * @return static
     */
    public function filter(\Closure $p);

    /**
     * 条件にマッチしない（関数が false を返す）要素のみを抽出する.
     *
     * @param \Closure $p 真偽値を返す関数
     * @return static
     */
    public function filterNot(\Closure $p);

    /**
     * 条件にマッチする（関数が true を返す）最初の要素を返す.
     *
     * @param \Closure $p 真偽値を返す関数
     * @return Option 最初に見つかった要素, 見つからなかった場合は None
     */
    public function find(\Closure $p): Option;

    /**
     * 要素を平坦化して返す.
     *
     * @return static
     */
    public function flatten();

    /**
     * 値の全要素に対して関数を適用し、その戻り値を平坦化して返す.
     *
     * @param \Closure $f 値を返す関数
     * @return static
     */
    public function flatMap(\Closure $f);

    /**
     * 全ての要素が条件にマッチする（関数が true を返す）かどうかを判定する.
     *
     * @param \Closure $p 真偽値を返す関数
     * @return bool 全ての要素が条件を満たす場合に true, そうでない場合は false
     */
    public function forAll(\Closure $p): bool;

    /**
     * 要素を指定された関数の戻り値でグループ化して返す.
     *
     * - $f に string が渡された場合は各要素から $f に該当する要素|プロパティを探し、それをキーとする
     * - $f に \Closure が渡された場合は各要素を引数として $f を実行し、それをキーとする
     *
     * @param string|\Closure $f
     * @return Map
     */
    public function groupBy($f): Map;

    /**
     * 値の先頭要素を返す, 要素がない場合は例外を投げる.
     *
     * @return mixed 先頭の要素
     */
    public function head();

    /**
     * 値の先頭要素を返す.
     *
     * @return Option 先頭の要素, 要素がない場合は None
     */
    public function headOption(): Option;

    /**
     * 値が空かどうかを判定する.
     *
     * @return bool 値が空の場合に true, そうでない場合に false
     */
    public function isEmpty(): bool;

    /**
     * 値の末尾(最終)要素を返し, 要素がない場合は例外を投げる.
     *
     * @return mixed 末尾の要素
     */
    public function last();

    /**
     * 値の末尾(最終)要素を返す.
     *
     * @return Option 末尾の要素, 要素がない場合は None
     */
    public function lastOption(): Option;

    /**
     * 値の全要素に対して関数を適用し, その戻り値を返す.
     *
     * @param \Closure $f 値を返す関数
     * @return static
     */
    public function map(\Closure $f);

    /**
     * 最大の要素を返す.
     *
     * @return mixed 最大の要素
     */
    public function max();

    /**
     * 関数を適用した結果が最大となる要素を返す.
     *
     * @param \Closure $f
     * @return mixed 最大の要素
     */
    public function maxBy(\Closure $f);

    /**
     * 最小の要素を返す.
     *
     * @return mixed 最小の要素
     */
    public function min();

    /**
     * 関数を適用した結果が最小となる要素を返す.
     *
     * @param \Closure $f
     * @return mixed 最小の要素
     */
    public function minBy(\Closure $f);

    /**
     * 要素を文字列化して結合する.
     *
     * @param string $sep
     * @return string
     */
    public function mkString(string $sep = ""): string;

    /**
     * 値が空でないかどうかを判定する.
     *
     * @return bool 値が空でない場合に true, そうでない場合に false
     */
    public function nonEmpty(): bool;

    /**
     * Returns a number of elements.
     *
     * @return int
     */
    public function size(): int;

    /**
     * Returns first `$n` elements.
     *
     * @param int $n
     * @return static
     */
    public function take(int $n);

    /**
     * Returns last `$n` elements.
     *
     * @param int $n
     * @return static
     */
    public function takeRight(int $n);

    /**
     * Convert to an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Convert to a Generator.
     *
     * @return \Generator
     */
    public function toGenerator(): \Generator;

    /**
     * Convert to a Seq.
     *
     * @return Seq
     */
    public function toSeq(): Seq;

}
