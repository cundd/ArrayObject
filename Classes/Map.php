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
 * Created 15.09.15 12:11
 */


namespace Cundd;

use UnexpectedValueException;

/**
 * Object-to-object data store
 *
 * @package Cundd
 */
class Map implements MapInterface
{
    /**
     * Map of the object hash to the key object
     *
     * @var array
     */
    private $hashToKeyObjectMap = array();

    /**
     * Map of the object hash to the value object
     *
     * @var array
     */
    private $hashToValueMap = array();

    /**
     * Map constructor.
     *
     * @param array $objects
     */
    public function __construct(array $objects = array())
    {
        foreach ($objects as $objectAndValue) {
            $this->assertPair($objectAndValue);
            $this->offsetSet($objectAndValue[0], $objectAndValue[1]);
        }
    }

    /**
     * Create a new map with the given pairs
     *
     * @param array $pair1 ...
     * @return Map
     */
    public static function createWithPairs($pair1)
    {
        $pairs = func_get_args();
        foreach ($pairs as $pair) {
            static::assertPair($pair);
        }
        return new static($pairs);
    }

    /**
     * Creates a copy of the Collection.
     *
     * @link  http://php.net/manual/en/arrayobject.getarraycopy.php
     * @return array a copy of the array. When the <b>Collection</b> refers to an object
     *        an array of the public properties of that object will be returned.
     * @since 5.0.0
     */
    public function getArrayCopy()
    {
        return $this->hashToValueMap;
    }

    /**
     * Returns the array of key objects
     *
     * @return object[]
     */
    public function getKeys()
    {
        return $this->hashToKeyObjectMap;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $currentKey = $this->hashKey();
        if (isset($this->hashToValueMap[$currentKey])) {
            return $this->hashToValueMap[$currentKey];
        }

        return null;
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->hashToKeyObjectMap);
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return current($this->hashToKeyObjectMap);
    }

    /**
     * Return the hash key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function hashKey()
    {
        return key($this->hashToKeyObjectMap);
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return (current($this->hashToKeyObjectMap) !== false);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->hashToKeyObjectMap);
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->hashToKeyObjectMap[$this->hash($offset)]);
    }

    /**
     * @see offsetExists()
     * @param object|string $keyObject Key object to lookup or it's hash
     * @return bool
     */
    public function exists($keyObject)
    {
        return $this->offsetExists($keyObject);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }

        return $this->hashToValueMap[$this->hash($offset)];
    }

    /**
     * @see offsetGet()
     * @param object|string $keyObject Key object to lookup or it's hash
     * @return mixed
     */
    public function get($keyObject)
    {
        return $this->offsetGet($keyObject);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $hash = $this->hash($offset);
        $this->hashToKeyObjectMap[$hash] = $offset;
        $this->hashToValueMap[$hash] = $value;
    }

    /**
     * @see offsetSet()
     * @param object|string $keyObject Key object to lookup or it's hash
     * @param mixed         $value
     */
    public function set($keyObject, $value)
    {
        $this->offsetSet($keyObject, $value);
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $hash = $this->hash($offset);
        unset($this->hashToKeyObjectMap[$hash]);
        unset($this->hashToValueMap[$hash]);
    }

    /**
     * Count elements of an object
     *
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *        </p>
     *        <p>
     *        The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->hashToKeyObjectMap);
    }

    /**
     * Applies the callback to the elements of the given arrays
     *
     * array_map() returns an array containing all the elements of array1 after applying the callback function to each one. The number of parameters that the callback function accepts should match the number of arrays passed to the array_map()
     *
     * @link http://php.net/manual/en/function.array-map.php
     *
     * @param callable $callback
     * @return CollectionInterface
     */
    public function map(callable $callback)
    {
        $result = new static();
        foreach ($this as $keyObject => $value) {
            $result->offsetSet($keyObject, $callback($keyObject, $value));
        }

        return $result;
    }

    /**
     * Filters elements of an array using a callback function
     *
     * Iterates over each value in the array passing them to the callback function. If the callback function returns true, the current value from array is returned into the result array. Array keys are preserved.
     *
     * @link http://php.net/manual/en/function.array-filter.php
     *
     * @param callable $callback The callback function to use
     * @param int      $flag     Flag determining what arguments are sent to callback: ARRAY_FILTER_USE_KEY / ARRAY_FILTER_USE_BOTH
     * @return CollectionInterface
     */
    public function filter(callable $callback, $flag = 0)
    {
        $result = new static();
        foreach ($this as $keyObject => $value) {
            if ($callback($keyObject, $value)) {
                $result->offsetSet($keyObject, $value);
            }
        }

        return $result;
    }

    /**
     * Join array elements with a string
     *
     * @param string $glue
     * @return string
     */
    public function implode($glue = '')
    {
        return implode($glue, $this->getArrayCopy());
    }


    /**
     * @param string|object $variable
     * @return string
     */
    protected function hash($variable)
    {
        if (is_string($variable)) {
            $hash = $variable;

        } elseif (is_object($variable)) {
            $hash = spl_object_hash($variable);

        } else {
            throw new UnexpectedValueException(
                sprintf('Can not create hash for variable of type "%s"', gettype($variable)),
                1442825536
            );
        }

        return $hash;
    }

    /**
     * @param array $objectAndValue
     * @return void
     */
    private static function assertPair($objectAndValue)
    {
        if (!is_array($objectAndValue)) {
            throw new \InvalidArgumentException('Constructor argument must be an array of arrays', 1442827041);
        }
        if (!isset($objectAndValue[0]) || count($objectAndValue) < 2) {
            throw new \InvalidArgumentException('Constructor argument must be an array of arrays', 1442827041);
        }
    }
}
