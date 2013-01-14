<?php

namespace Sly\Bundle\VMBundle\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Configuration.
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Config
{
    /**
     * Get VM defaults.
     * 
     * @return array
     */
    public static function getVMDefaults()
    {
        return Yaml::parse(
            file_get_contents(
                __DIR__.'/../Resources/config/vm/defaults.yml'
            )
        );
    }

    /**
     * Get Git submodules repositories.
     * 
     * @return array
     */
    public static function getGitSubmodulesRepositories()
    {
        return Yaml::parse(
            file_get_contents(
                __DIR__.'/../Resources/config/vm/gitSubmodules.yml'
            )
        );
    }
}
