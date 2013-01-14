<?php

namespace Sly\Bundle\VMBundle\Generator;

use Sly\Bundle\VMBundle\Config\Config;

/**
 * GitSubmodule.
 *
 * @uses \IteratorAggregate
 * @author Cédric Dugat <cedric@dugat.me>
 */
class GitSubmoduleCollection implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $repositories;

    /**
     * @var \ArrayIterator
     */
    private $submodules;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->repositories = Config::getGitSubmodulesRepositories();
        $this->submodules   = array();
    }

    /**
     * Get iterator.
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->submodules;
    }

    /**
     * Add.
     * 
     * @param string $key Git submodule key
     */
    public function add($key)
    {
        $this->submodules[] = $key;
    }

    /**
     * Get Git submodules file content.
     * 
     * @return string
     */
    public function getFileContent()
    {
        $lines = array();

        foreach ($this->submodules as $submodule) {
            if (array_key_exists($submodule, $this->repositories)) {
                $lines[] = sprintf("[submodule \"%s\"]\n    path = %s\n    url = %s\n\n",
                    $this->repositories[$submodule]['path'],
                    $this->repositories[$submodule]['path'],
                    $this->repositories[$submodule]['url']
                );
            }
        }

        return implode('', $lines);
    }

    /**
     * Clear entries
     */
    public function clear()
    {
        $this->submodules = new \ArrayIterator();
    }
}
