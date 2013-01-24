<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * VM Box Squeeze Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VMBoxSqueezeElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'vmBoxSqueeze';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->isDebianBox();
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:VMBoxSqueezeElement.html.twig', array(
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

        $sourcesFilePath = sprintf('%s/%s', $filesPath, 'sources.list');

        $sourcesListContent = $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/Files/Sources:squeezeSources.list.html.twig', array(
                'vm' => $this->getVM(),
            ));

        $this->getGenerator()->getFilesystem()->touch($sourcesFilePath);
        file_put_contents($sourcesFilePath, $sourcesListContent);
    }
}
