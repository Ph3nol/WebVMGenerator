<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

use Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface;

/**
 * PuppetElement collection.
 *
 * @uses \IteratorAggregate
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PuppetElementCollection implements \IteratorAggregate
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
     * @param \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface $element Puppet element
     */
    public function add(PuppetElementInterface $element)
    {
        $this->coll[$element->getName()] = $element;
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
