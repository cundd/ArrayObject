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


class ArrayObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayObject
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new ArrayObject(['a', 'b', 'c']);
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
        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['A', 'B', 'C'], $result->getArrayCopy());

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
        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertSame(1, $result->count());
        $this->assertSame(['a'], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function mergeTest()
    {
        $result = $this->fixture->merge([1, 2, 3]);
        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertSame(6, $result->count());
        $this->assertSame(['a', 'b', 'c', 1, 2, 3], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function multiMergeTest()
    {
        $result = $this->fixture->merge([1, 2, 3], [4, 5, 6]);
        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertSame(9, $result->count());
        $this->assertSame(['a', 'b', 'c', 1, 2, 3, 4, 5, 6], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function createFromStringTest()
    {
        $result = ArrayObject::createFromString(',', 'a,b,c');
        $this->assertInstanceOf(ArrayObject::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->getArrayCopy());
    }
}
