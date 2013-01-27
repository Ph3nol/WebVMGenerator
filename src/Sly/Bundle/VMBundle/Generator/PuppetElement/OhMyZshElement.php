<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * OhMyZsh Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class OhMyZshElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ohMyZsh';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getOhMyZsh();
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:OhMyZshElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess()
    {
        $filesPath = sprintf(
            '%s/%s/files',
            $this->getGenerator()->getKernelRootDir(),
            $this->getVM()->getCachePath()
        );

        $ohMyZshPlugins = array('git', 'symfony', 'symfony2');

        $zshrcContent = $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/Files:zshrc.html.twig', array(
                'vm'             => $this->getVM(),
                'ohMyZshPlugins' => $ohMyZshPlugins,
            ));

        $zshrcFilepath = sprintf('%s/.zshrc', $filesPath);

        $this->getGenerator()->getFilesystem()->touch($zshrcFilepath);
        file_put_contents($zshrcFilepath, $zshrcContent);
    }
}
