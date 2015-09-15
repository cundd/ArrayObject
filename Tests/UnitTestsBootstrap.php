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
 * Created 15.09.15 12:28
 */

/**
 * Bootstrapping for unit tests
 *
 * @package Iresults\ResourceBooking\Tests
 */
class UnitTestsBootstrap
{
    /**
     * Bootstrap the testing environment
     */
    public function bootstrapSystem()
    {
        $this->registerAutoloader();
    }

    /**
     * Require composer's autoloader
     */
    protected function registerAutoloader()
    {
        require_once __DIR__.'/../vendor/autoload.php';
    }
}

$bootstrap = new UnitTestsBootstrap();
$bootstrap->bootstrapSystem();
unset($bootstrap);
