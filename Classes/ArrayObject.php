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

/**
 * Array extension to support array functions
 *
 * @package Cundd
 */
class ArrayObject extends \ArrayObject
{
    /**
     * Applies the callback to the elements of the given arrays
     *
     * array_map() returns an array containing all the elements of array1 after applying the callback function to each one. The number of parameters that the callback function accepts should match the number of arrays passed to the array_map()
     *
     * @link http://php.net/manual/en/function.array-map.php
     *
     * @param callable $callback
     * @return ArrayObject
     */
    public function map($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Argument "callback" is not callable', 1442311974);
        }

        return new static(array_map($callback, $this->getArrayCopy()));
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
     * @return ArrayObject
     */
    public function filter($callback, $flag = 0)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Argument "callback" is not callable', 1442311975);
        }

        return new static(array_filter($this->getArrayCopy(), $callback, $flag));
    }

    /**
     * Merge one or more arrays
     *
     * Merges the elements of one or more arrays together so that the values of one are appended to the end of the previous one. It returns the resulting array.
     *
     * If the input arrays have the same string keys, then the later value for that key will overwrite the previous one. If, however, the arrays contain numeric keys, the later value will not overwrite the original value, but will be appended.
     *
     * Values in the input array with numeric keys will be renumbered with incrementing keys starting from zero in the result array.
     *
     * @param array ...$array1
     * @return ArrayObject
     */
    public function merge(array $array1)
    {
        $arguments = func_get_args();
        array_unshift($arguments, $this->getArrayCopy());
        $merged = call_user_func_array('array_merge', $arguments);

        return new static($merged);
    }

    /**
     * Split a string by string
     *
     * @param string $delimiter
     * @param string $input
     * @return ArrayObject
     */
    public static function createFromString($delimiter, $input)
    {
        if (!is_string($delimiter)) {
            throw new \InvalidArgumentException(
                sprintf('Argument "delimiter" must be of type string "%s" given', gettype($delimiter)),
                1442318390
            );
        }
        if (!is_string($input)) {
            throw new \InvalidArgumentException(
                sprintf('Argument "input" must be of type string "%s" given', gettype($delimiter)),
                1442318391
            );
        }
        if ($input === '') {
            return new static();
        }

        return new static(explode($delimiter, $input));
    }
}
