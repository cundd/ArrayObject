<?php
/*
 *  Copyright notice
 *
 *  (c) 2015 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *  Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * @author COD
 * Created 15.09.15 12:21
 */


namespace Cundd;


class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new Collection(['a', 'b', 'c']);
    }

    protected function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function mapTest()
    {
        $result = $this->fixture->map(
            function ($item) {
                return strtoupper($item);
            }
        );
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['A', 'B', 'C'], $result->getArrayCopy());

        $result = $this->fixture->map('strtoupper');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['A', 'B', 'C'], $result->getArrayCopy());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442311974
     */
    public function mapWithoutCallableTest()
    {
        $this->fixture->map(null);
    }

    /**
     * @test
     */
    public function filterTest()
    {
        $result = $this->fixture->filter(
            function ($item) {
                return $item === 'a';
            },
            $flag = 0
        );
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(1, $result->count());
        $this->assertSame(['a'], $result->getArrayCopy());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442311975
     */
    public function filterWithoutCallableTest()
    {
        $this->fixture->filter(null);
    }

    /**
     * @test
     */
    public function mergeTest()
    {
        $result = $this->fixture->merge([1, 2, 3]);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(6, $result->count());
        $this->assertSame(['a', 'b', 'c', 1, 2, 3], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function multiMergeTest()
    {
        $result = $this->fixture->merge([1, 2, 3], [4, 5, 6]);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(9, $result->count());
        $this->assertSame(['a', 'b', 'c', 1, 2, 3, 4, 5, 6], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function implodeTest()
    {
        $this->assertSame('abc', $this->fixture->implode());
        $this->assertSame('a,b,c', $this->fixture->implode(','));
}
    /**
     * @test
     */
    public function createFromStringTest()
    {
        $result = Collection::createFromString(',', 'a,b,c');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function createFromStringSingleElementTest()
    {
        $result = Collection::createFromString(',', 'a');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(1, $result->count());
        $this->assertSame(['a'], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function createFromStringWithEmptyStringTest()
    {
        $result = Collection::createFromString(',', '');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(0, $result->count());
        $this->assertSame([], $result->getArrayCopy());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318390
     */
    public function createFromStringWithInvalidDelimiterTest()
    {
        Collection::createFromString(null, 'a,b,c');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318390
     */
    public function createFromStringWithInvalidDelimiterIntTest()
    {
        Collection::createFromString(1, 'a,b,c');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318390
     */
    public function createFromStringWithInvalidDelimiterDoubleTest()
    {
        Collection::createFromString(1.0, 'a,b,c');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318390
     */
    public function createFromStringWithInvalidDelimiterArrayTest()
    {
        Collection::createFromString([], 'a,b,c');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318390
     */
    public function createFromStringWithInvalidDelimiterObjectTest()
    {
        Collection::createFromString(new \stdClass(), 'a,b,c');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318391
     */
    public function createFromStringWithInvalidInputTest()
    {
        Collection::createFromString(',', null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318391
     */
    public function createFromStringWithInvalidInputIntTest()
    {
        Collection::createFromString(',', 1);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318391
     */
    public function createFromStringWithInvalidInputDoubleTest()
    {
        Collection::createFromString(',', 1.0);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318391
     */
    public function createFromStringWithInvalidInputArrayTest()
    {
        Collection::createFromString(',', []);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionCode 1442318391
     */
    public function createFromStringWithInvalidInputObjectTest()
    {
        Collection::createFromString(',', new \stdClass());
    }
}
