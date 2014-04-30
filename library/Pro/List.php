<?php
/**
 * Created by PhpStorm.
 * User: laslo
 * Date: 26/04/14
 * Time: 15:28
 */

class Pro_List implements Iterator, Countable {

    /**
     * @var array
     */
    private $objects;

    /**
     * @var array
     */
    private $object_keys;

    function __construct(array $objects) {
        $this->objects = $objects;
        $this->object_keys = array_keys($objects);
    }

    /**
     * Iterator implementation.
     */
    public function rewind() {
        reset($this->objects);
    }

    /**
     * Iterator implementation.
     */
    public function current() {
        return current($this->objects);
    }

    /**
     * Iterator implementation.
     */
    public function key() {
        return key($this->objects);
    }

    /**
     * Iterator implementation.
     */
    public function next() {
        next($this->objects);
    }

    /**
     * Iterator implementation.
     */
    public function valid() {
        return current($this->objects) !== false;
    }

    /**
     * Countable implementation.
     */
    public function count() {
        return count($this->object_keys);
    }

    public function toArray() {
        return $this->objects;
    }

    /**
     * Filter list values and return result as new list
     * Multiple filters are available: first parameter might be as a filter map in format of associative array
     *
     * @param $property - property for filtering or multiple filter map
     * @param $value - value for filtering
     * @return Pro_List
     */
    public function filter($property, $value = null) {
        $condition = (is_array($property)) ?
            $property :
            array($property => $value,);

        $filtered = array();
        foreach ($this->object_keys as $key) {
            $object = $this->objects[$key];
            $isSuitable= true;
            foreach ($condition as $condProperty => $condValue) {
                if (!isset($object[$condProperty]) || $object[$condProperty] !== $condValue) {
                    $isSuitable = false;
                    break;
                }
            }
            if (!$isSuitable) {
                continue;
            }
            $filtered[] = $object;
        }

        return new Pro_List($filtered);
    }

    public function find($property, $value = null) {
        $condition = (is_array($property)) ?
            $property :
            array($property => $value,);

        foreach ($this->object_keys as $key) {
            $object = $this->objects[$key];
            foreach ($condition as $condProperty => $condValue) {
                if (isset($object[$condProperty]) && $object[$condProperty] === $condValue) {
                    return $object;
                }
            }
        }

        return null;
    }

    /**
     * Sort list items by some property and direction
     * @param $property
     * @param bool $asc
     * @return $this
     */
    public function order($property, $asc = true) {
        $compareFunction = $this->_getCompareFunction($property, $asc);
        usort($this->objects, $compareFunction);
        $this->object_keys = array_keys($this->objects);
        return $this;
    }

    /**
     * Collect all values of some property from list items
     * @param $property
     */
    public function collect($property) {
        $collected = array();
        foreach ($this->object_keys as $key) {
            $object = $this->objects[$key];
            if (isset($object[$property])) {
                $collected[] = $object[$property];
            }
        }
        return $collected;
    }

    /**
     * Return first element in the list
     * @return mixed
     */
    public function first() {
        if (count($this->object_keys) === 0)
            return null;
        return $this->objects[reset($this->object_keys)];
    }

    /**
     * Prepare comparison function for ordering by some property and direction
     * @param $property
     * @param $asc
     * @return callable|int
     */
    private function _getCompareFunction($property, $asc) {
        return function ($a, $b) use ($property, $asc) {
            $valueA = $a[$property];
            $valueB = $b[$property];
            return ($asc ? 1 : -1) * strcasecmp($valueA, $valueB);
        };
    }
}