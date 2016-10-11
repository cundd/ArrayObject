<?php
/*
 *  Copyright notice
 *
 *  (c) 2016 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
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
 * Created 11.10.16 14:28
 */


namespace Cundd;


use Cundd\Exception\InvalidArgumentTypeException;

class TypedCollection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $managedType;

    /**
     * Construct a new array object
     *
     * @link  http://php.net/manual/en/arrayobject.construct.php
     * @param array|object|string $inputOrType    The input parameter accepts an array, an Object or a string defining the type
     * @param int                 $flags          Flags to control the behaviour of the ArrayObject object.
     * @param string              $iterator_class Specify the class that will be used for iteration of the ArrayObject object. ArrayIterator is the default class used.
     * @since 5.0.0
     *
     */
    public function __construct($inputOrType, $flags = 0, $iterator_class = "ArrayIterator")
    {
        if (!$inputOrType) {
            throw new \InvalidArgumentException('Required argument input not set or empty');
        }
        if (is_string($inputOrType)) {
            $this->setManagedType($inputOrType);
            $inputOrType = [];
        } else {
            $this->setManagedTypeFromInput($inputOrType);
            $this->validateElementsType($inputOrType);
        }

        parent::__construct($inputOrType, $flags, $iterator_class);
    }

    /**
     * @return string
     * @throws \Exception if the managed type is not defined
     */
    public function getManagedType()
    {
        if (!$this->managedType) {
            throw new \Exception('Managed type not defined');
        }

        return $this->managedType;
    }

    /**
     * Sets the value at the specified index to newval
     *
     * @link  http://php.net/manual/en/arrayobject.offsetset.php
     * @param mixed $index    <p>
     *                        The index being set.
     *                        </p>
     * @param mixed $newValue <p>
     *                        The new value for the <i>index</i>.
     *                        </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($index, $newValue)
    {
        $this->validateElementType($newValue);
        parent::offsetSet($index, $newValue);
    }

    /**
     * Appends the value
     *
     * @link  http://php.net/manual/en/arrayobject.append.php
     * @param mixed $value <p>
     *                     The value being appended.
     *                     </p>
     * @return void
     * @since 5.0.0
     */
    public function append($value)
    {
        $this->validateElementType($value);
        parent::append($value);
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
        return new Collection(array_map($callback, $this->getArrayCopy()));
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
    public function mapTyped(callable $callback)
    {
        $newValues = array_map($callback, $this->getArrayCopy());
        if (empty($newValues)) {
            return new static($this->getManagedType());
        }

        return new static($newValues);
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
        return new Collection(array_filter($this->getArrayCopy(), $callback, $flag));
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
    public function filterTyped(callable $callback, $flag = 0)
    {
        $newValues = array_filter($this->getArrayCopy(), $callback, $flag);
        if (empty($newValues)) {
            return new static($this->getManagedType());
        }

        return new static($newValues);
    }

    /**
     * @param array|\Traversable $elements
     */
    private function validateElementsType($elements)
    {
        foreach ($elements as $element) {
            $this->validateElementType($element);
        }
    }

    /**
     * @param mixed $element
     */
    private function validateElementType($element)
    {
        if (!is_a($element, $this->getManagedType())) {
            $exceptionMessage = sprintf(
                'Element is not of expected type %s, %s given',
                $this->managedType,
                $this->detectType($element)
            );
            throw new InvalidArgumentTypeException($exceptionMessage);
        }
    }

    /**
     * @param mixed $element
     * @return string
     */
    private function detectType($element)
    {
        return is_object($element) ? get_class($element) : gettype($element);
    }

    /**
     * @param $input
     * @return mixed|null
     */
    private function getFirstElementOfInput($input)
    {
        if ($input instanceof \Traversable) {
            $arrayCopy = iterator_to_array($input);

            return reset($arrayCopy);
        } elseif (is_array($input)) {
            $arrayCopy = array_values($input);

            return array_shift($arrayCopy);
        }

        throw new \InvalidArgumentException('Input is neither an array nor a object');
    }

    /**
     * @param $input
     */
    private function setManagedTypeFromInput($input)
    {
        $firstElement = $this->getFirstElementOfInput($input);
        if (!is_object($firstElement)) {
            throw new \InvalidArgumentException('First input element must be a object');
        }
        $this->managedType = $this->detectType($firstElement);
    }

    /**
     * @param $type
     */
    private function setManagedType($type)
    {
        if (!class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Class %s does not exist', $type));
        }
        $this->managedType = $type;
    }
}
