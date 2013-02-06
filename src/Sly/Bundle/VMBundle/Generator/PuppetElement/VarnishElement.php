<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Varnish Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VarnishElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'varnish';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getVarnish();
    }

    /**
     * {@inheritDoc}
     */
    public function getGitModules()
    {
        return array(
            array('modules/varnish', 'https://github.com/zylon-internet/puppet-varnish.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:VarnishElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }

    /**
     * {@inheritDoc}
     */
    // public function postProcess()
    // {
    //     $varnishFilesPath = sprintf(
    //         '%s/%s/files',
    //         $this->getGenerator()->getKernelRootDir(),
    //         $this->getVM()->getCachePath()
    //     );

    //     $this->getGenerator()->getFilesystem()->mkdir(array(
    //         $varnishFilesPath,
    //         $varnishFilesPath.'/php',
    //     ), 0777);

    //     $varnishFilesOptions = array(
    //         'vm' => $this->getVM(),
    //     );

    //     $varnishDefaultVclContent = $this->getGenerator()->getTemplating()
    //         ->render('SlyVMBundle:VM/Files/Varnish:default.vcl.html.twig', $varnishFilesOptions);

    //     file_put_contents($varnishFilesPath.'/varnish/default.vcl', $varnishDefaultVclContent);
    // }
}
