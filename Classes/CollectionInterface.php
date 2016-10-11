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
 * Created 21.09.15 10:37
 */


namespace Cundd;

use Countable;

/**
 * Interface for array functions
 *
 * @package Cundd
 */
interface CollectionInterface extends Countable
{
    /**
     * Creates a copy of the Collection.
     * @link http://php.net/manual/en/arrayobject.getarraycopy.php
     * @return array a copy of the array. When the <b>Collection</b> refers to an object
     * an array of the public properties of that object will be returned.
     * @since 5.0.0
     */
    public function getArrayCopy();

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
    public function map(callable $callback);

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
    public function filter(callable $callback, $flag = 0);

    /**
     * Join array elements with a string
     *
     * @param string $glue
     * @return string
     */
    public function implode($glue = '');
}
