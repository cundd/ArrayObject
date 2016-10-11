<?php
/*
 *  Copyright notice
 *
 *  Daniel Corn <info@cundd.net>,
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


namespace Cundd\Tests\Unit;


use ArrayObject;
use Cundd\Collection;
use Cundd\Tests\Unit\Fixtures\Address;
use Cundd\Tests\Unit\Fixtures\Person;
use Cundd\TypedCollection;

class TypedCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TypedCollection
     */
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new TypedCollection([new Person('Daniel'), new Person('Gert'), new Person('Loren')]);
    }

    protected function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function createWithObjectTest()
    {
        $inputArray = [new Person('Daniel'), new Person('Gert'), new Person('Loren')];
        $collection = new TypedCollection(new ArrayObject($inputArray));

        $this->assertEquals($inputArray, $collection->getArrayCopy());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Input is neither an array nor a object
     */
    public function throwForInvalidInputTest()
    {
        new TypedCollection(123);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Class NotAClass does not exist
     */
    public function throwForInvalidClassInputTest()
    {
        new TypedCollection('NotAClass');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Required argument input not set or empty
     */
    public function throwEmptyInputTest()
    {
        new TypedCollection(null);
    }

    /**
     * @test
     * @expectedException \Cundd\Exception\InvalidArgumentTypeException
     */
    public function throwForMixedElementsTest()
    {
        new TypedCollection([new Person(), new Person(), new Address()]);
    }

    /**
     * @test
     */
    public function mapTest()
    {
        $result = $this->fixture->map(
            function (Person $item) {
                return strtoupper($item->getName());
            }
        );
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['DANIEL', 'GERT', 'LOREN'], $result->getArrayCopy());

        $result = $this->fixture->map('strtoupper');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['DANIEL', 'GERT', 'LOREN'], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function mapTypedTest()
    {
        $result = $this->fixture->mapTyped(
            function (Person $item) {
                return new Person(strtoupper($item->getName()));
            }
        );
        $this->assertInstanceOf(TypedCollection::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame('Daniel,Gert,Loren', $this->fixture->implode(','));
    }

    /**
     * @test
     */
    public function filterTest()
    {
        $result = $this->fixture->filter(
            function (Person $item) {
                return $item->getName() === 'Gert';
            },
            $flag = 0
        );
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertSame(1, $result->count());
        $this->assertEquals([1 => new Person('Gert')], $result->getArrayCopy());
    }

    /**
     * @test
     */
    public function multiMergeTest()
    {
        $result = $this->fixture->merge(
            [new Person(), new Person(), new Person()],
            [new Person(), new Person(), new Person()]
        );
        $this->assertInstanceOf(TypedCollection::class, $result);
        $this->assertSame(9, $result->count());
    }

    /**
     * @test
     */
    public function implodeTest()
    {
        $this->assertSame('DanielGertLoren', $this->fixture->implode());
        $this->assertSame('Daniel,Gert,Loren', $this->fixture->implode(','));
    }
}
