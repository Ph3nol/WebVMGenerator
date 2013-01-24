<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * PHP Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class PhpElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'php';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getPhp();
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:PhpElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess()
    {
        $phpFilesPath = sprintf(
            '%s/%s/files',
            $this->getGenerator()->getKernelRootDir(),
            $this->getVM()->getCachePath()
        );

        $this->getGenerator()->getFilesystem()->mkdir(array(
            $phpFilesPath,
            $phpFilesPath.'/php',
        ), 0777);

        $phpIniRenderOptions = array(
            'vm'     => $this->getVM(),
            'xDebug' => in_array('xdebug', $this->getVM()->getPhpModules()),
        );

        $phpIniContent = $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/Files/PHP:php.ini.html.twig', $phpIniRenderOptions);

        $phpCliIniContent = $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/Files/PHP:phpCli.ini.html.twig', $phpIniRenderOptions);

        file_put_contents($phpFilesPath.'/php/php.ini', $phpIniContent);
        file_put_contents($phpFilesPath.'/php/php-cli.ini', $phpCliIniContent);
    }
}
