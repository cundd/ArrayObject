<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 23.09.15
 * Time: 11:49
 */
namespace Cundd;


/**
 * Object-to-object data store
 *
 * @package Cundd
 */
interface MapInterface extends \Iterator, \ArrayAccess, CollectionInterface
{
    /**
     * Create a new map with the given pairs
     *
     * @param array $pair1 ...
     * @return Map
     */
    public static function createWithPairs($pair1);

    /**
     * Returns the array of key objects
     *
     * @return object[]
     */
    public function getKeys();

    /**
     * Returns if the given key exists
     *
     * @param object|string $keyObject Key object to lookup or it's hash
     * @return bool
     */
    public function exists($keyObject);

    /**
     * Returns the value for the given key
     *
     * @param object|string $keyObject Key object to lookup or it's hash
     * @return mixed
     */
    public function get($keyObject);

    /**
     * Sets the value for the given key
     *
     * @param object|string $keyObject Key object to lookup or it's hash
     * @param mixed         $value
     */
    public function set($keyObject, $value);
}
