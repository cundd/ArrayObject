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
class Collection extends AbstractCollection
{
    /**
     * Split a string by string
     *
     * @param string $delimiter
     * @param string $input
     * @return Collection
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
                sprintf('Argument "input" must be of type string "%s" given', gettype($input)),
                1442318391
            );
        }
        if ($input === '') {
            return new static();
        }

        return new static(explode($delimiter, $input));
    }
}
