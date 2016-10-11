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


namespace Cundd\Tests\Unit;


use Cundd\CollectionInterface;
use Cundd\Map;
use stdClass;

class MapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Map
     */
    protected $fixture;

    protected function setUp()
    {
        $objectA = new stdClass();
        $objectA->character = 'a';

        $objectB = new stdClass();
        $objectB->character = 'b';

        $objectC = new stdClass();
        $objectC->character = 'c';

        $this->fixture = new Map(
            [
                [$objectA, 'a'],
                [$objectB, 'b'],
                [$objectC, 'c'],
            ]
        );
    }

    protected function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function currentTest()
    {
        $this->assertSame('a', $this->fixture->current());
    }

    /**
     * @test
     */
    public function nextTest()
    {
        $this->assertSame('a', $this->fixture->current());
        $this->fixture->next();
        $this->assertSame('b', $this->fixture->current());
        $this->fixture->next();
        $this->assertSame('c', $this->fixture->current());
        $this->fixture->next();
        $this->assertNull($this->fixture->current());
    }


    /**
     * @test
     */
    public function keyTest()
    {
        $this->assertInstanceOf(stdClass::class, $this->fixture->key());
        $this->assertSame('a', $this->fixture->key()->character);
    }


    /**
     * @test
     */
    public function hashKeyTest()
    {
        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'xyz'],
            ]
        );

        $this->assertSame(spl_object_hash($object), $this->fixture->hashKey());
    }

    /**
     * @test
     */
    public function validTest()
    {
        $this->assertTrue($this->fixture->valid());
        $this->fixture->next();
        $this->assertTrue($this->fixture->valid());
        $this->fixture->next();
        $this->assertTrue($this->fixture->valid());
        $this->fixture->next();
        $this->assertFalse($this->fixture->valid());
    }

    /**
     * @test
     */
    public function rewindTest()
    {
        $this->assertSame('a', $this->fixture->current());
        $this->fixture->next();
        $this->assertSame('b', $this->fixture->current());
        $this->fixture->next();
        $this->assertSame('c', $this->fixture->current());
        $this->fixture->next();
        $this->assertNull($this->fixture->current());

        $this->fixture->rewind();
        $this->assertSame('a', $this->fixture->current());
    }

    /**
     * @test
     */
    public function offsetExistsTest()
    {
        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'xyz'],
            ]
        );


        $this->assertTrue($this->fixture->offsetExists($object));
        $this->assertTrue($this->fixture->exists($object));
        $this->assertTrue(isset($this->fixture[$object]));

        $this->assertTrue($this->fixture->offsetExists(spl_object_hash($object)));
        $this->assertTrue($this->fixture->exists(spl_object_hash($object)));
        $this->assertTrue(isset($this->fixture[spl_object_hash($object)]));

        $object2 = new stdClass();
        $this->assertFalse($this->fixture->offsetExists($object2));
        $this->assertFalse($this->fixture->exists($object2));
        $this->assertFalse(isset($this->fixture[$object2]));

        $this->assertFalse($this->fixture->offsetExists(spl_object_hash($object2)));
        $this->assertFalse($this->fixture->exists(spl_object_hash($object2)));
        $this->assertFalse(isset($this->fixture[spl_object_hash($object2)]));
    }

    /**
     * @test
     */
    public function offsetGetTest()
    {
        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'a'],
            ]
        );


        $this->assertEquals('a', $this->fixture->offsetGet($object));
        $this->assertEquals('a', $this->fixture->get($object));
        $this->assertEquals('a', $this->fixture[$object]);

        $this->assertEquals('a', $this->fixture->offsetGet(spl_object_hash($object)));
        $this->assertEquals('a', $this->fixture->get(spl_object_hash($object)));
        $this->assertEquals('a', $this->fixture[spl_object_hash($object)]);

        $object2 = new stdClass();
        $this->assertNull($this->fixture->offsetGet($object2));
        $this->assertNull($this->fixture->get($object2));
        $this->assertNull($this->fixture[$object2]);

        $this->assertNull($this->fixture->offsetGet(spl_object_hash($object2)));
        $this->assertNull($this->fixture->get(spl_object_hash($object2)));
        $this->assertNull($this->fixture[spl_object_hash($object2)]);
    }

    /**
     * @test
     */
    public function offsetSetTest()
    {
        $object = new stdClass();
        $this->fixture = new Map();

        $this->fixture->offsetSet($object, 'a');
        $this->assertEquals('a', $this->fixture->offsetGet($object));
        $this->assertEquals('a', $this->fixture->offsetGet(spl_object_hash($object)));

        $this->fixture->set($object, 'm');
        $this->assertEquals('m', $this->fixture->offsetGet($object));
        $this->assertEquals('m', $this->fixture->offsetGet(spl_object_hash($object)));

        $this->fixture[$object] = 'v';
        $this->assertEquals('v', $this->fixture->offsetGet($object));
        $this->assertEquals('v', $this->fixture->offsetGet(spl_object_hash($object)));
    }

    /**
     * @test
     */
    public function offsetUnsetTest()
    {
        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'a'],
            ]
        );

        $this->fixture->offsetUnset($object);
        $this->assertNull($this->fixture->offsetGet($object));
        $this->assertNull($this->fixture[$object]);

        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'a'],
            ]
        );
        unset($this->fixture[$object]);
        $this->assertNull($this->fixture->offsetGet($object));
        $this->assertNull($this->fixture[$object]);


    }

    /**
     * @test
     */
    public function countTest()
    {
        $this->assertEquals(3, $this->fixture->count());

        $object = new stdClass();
        $this->fixture = new Map(
            [
                [$object, 'a'],
            ]
        );
        $this->assertEquals(1, $this->fixture->count());

        $this->fixture->offsetSet(new stdClass(), 123);
        $this->assertEquals(2, $this->fixture->count());

        $this->fixture->offsetUnset($object);
        $this->assertEquals(1, $this->fixture->count());

    }

    /**
     * @test
     */
    public function iteratorTest()
    {
        $iteratorCount = 0;
        foreach ($this->fixture as $keyObject => $value) {
            $iteratorCount++;
            /** @var object $keyObject */
            $this->assertInstanceOf(stdClass::class, $keyObject);
            $this->assertInternalType('string', $value);
            $this->assertEquals($value, $keyObject->character);
        }
        $this->assertSame(3, $iteratorCount);
    }

    /**
     * @test
     */
    public function getArrayCopyTest()
    {
        $this->assertSame(['a', 'b', 'c'], array_values($this->fixture->getArrayCopy()));
    }

    /**
     * @test
     */
    public function getKeysTest()
    {
        $objectA = new stdClass();
        $objectA->character = 'a';

        $objectB = new stdClass();
        $objectB->character = 'b';

        $objectC = new stdClass();
        $objectC->character = 'c';

        $this->fixture = new Map(
            [
                [$objectA, 'a'],
                [$objectB, 'b'],
                [$objectC, 'c'],
            ]
        );

        $this->assertSame([$objectA, $objectB, $objectC], array_values($this->fixture->getKeys()));
    }

    /**
     * @test
     */
    public function mapTest()
    {
        $result = $this->fixture->map(
            function ($keyObject, $value) {
                /** @var object $keyObject */
                assert($value === $keyObject->character);

                return strtoupper($keyObject->character);
            }
        );
        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertInstanceOf(Map::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['A', 'B', 'C'], array_values($result->getArrayCopy()));
    }

    /**
     * @test
     */
    public function filterTest()
    {
        $result = $this->fixture->filter(
            function ($item) {
                return $item->character === 'a';
            },
            $flag = 0
        );
        $this->assertInstanceOf(CollectionInterface::class, $result);
        $this->assertInstanceOf(Map::class, $result);
        $this->assertSame(1, $result->count());
        $this->assertSame(['a'], array_values($result->getArrayCopy()));
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
    public function createWithEmptyArgumentTest()
    {
        new Map(array());
    }

    /**
     * @test
     */
    public function createWithNullValueArgumentTest()
    {
        new Map(
            array(
                [new stdClass(), null]
            )
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function createWithInvalidArgumentTest()
    {
        new Map(array(new stdClass(),));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function createWithInvalidArgument2Test()
    {
        new Map(array(array()));
    }

    /**
     * @test
     */
    public function createWithPairTest()
    {
        $map = Map::createWithPairs(
            [new stdClass(), 'a'],
            [new stdClass(), 'b'],
            [new stdClass(), 'c']
        );
        $this->assertSame(['a', 'b', 'c'], array_values($map->getArrayCopy()));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function createWithInvalidPairTest()
    {
        $map = Map::createWithPairs(
            new stdClass(),
            [new stdClass(), 'b'],
            [new stdClass(), 'c']
        );
        $this->assertSame(['a', 'b', 'c'], array_values($map->getArrayCopy()));
    }
}
