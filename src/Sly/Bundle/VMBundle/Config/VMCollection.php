<?php

namespace Sly\Bundle\VMBundle\Config;

/**
 * Virtual Machines collection.
 *
 * @uses \IteratorAggregate
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VMCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    protected $coll;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->coll = new \ArrayIterator();
    }

    /**
     * Get iterator.
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->coll;
    }

    /**
     * Add.
     * 
     * @param string $vmName   VM name
     * @param array  $vmConfig VM configuration
     */
    public function add($vmName, $vmConfig)
    {
        $this->coll[$vmName] = $vmConfig;
    }

    /**
     * Get.
     * 
     * @param string $name Configuration name
     *
     * @return array
     */
    public function get($name)
    {
        return isset($this->coll[$name]) ? $this->coll[$name] : null;
    }

    /**
     * Has.
     * 
     * @param string $name Configuration name
     *
     * @return array
     */
    public function has($name)
    {
        return (bool) isset($this->coll[$name]);
    }

    /**
     * Count.
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->coll);
    }

    /**
     * Clear entries
     */
    public function clear()
    {
        $this->coll = new \ArrayIterator();
    }
}
