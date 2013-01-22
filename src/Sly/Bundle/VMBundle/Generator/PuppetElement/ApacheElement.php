<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * Apache Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ApacheElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'apache';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getApache();
    }

    /**
     * {@inheritDoc}
     */
    public function getGitSubmodules()
    {
        return array(
            array('modules/apache', 'https://github.com/example42/puppet-apache.git'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:ApacheElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess()
    {
        $apacheFilesPath = sprintf(
            '%s/%s/files/apache',
            $this->getGenerator()->getKernelRootDir(),
            $this->getVM()->getCachePath()
        );

        $this->getGenerator()->getFilesystem()->mkdir(array(
            $apacheFilesPath,
            $apacheFilesPath.'/sites-enabled',
        ), 0777);

        $vhosts = array(
            $this->getVM()->getHostname() => $this->getVM()->getApacheRootDir(),
        );

        $vhostIndex = 0;

        foreach ($vhosts as $vhostHostname => $vhostRootDir) {
            $vhostIndex++;

            $vhostFilename = sprintf('%d-%s.conf', $vhostIndex * 10, $vhostHostname);
            $vhostFilepath = sprintf('%s/sites-enabled/%s', $apacheFilesPath, $vhostFilename);

            $vhostContent = $this->getGenerator()->getTemplating()
                ->render('SlyVMBundle:VM/Files/Apache:defaultVhost.html.twig', array(
                    'vm'    => $this->getVM(),
                    'vHost' => array(
                        'hostname' => $vhostHostname,
                        'rootDir'  => $vhostRootDir,
                    ),
                ));

            $this->getGenerator()->getFilesystem()->touch($vhostFilepath);
            file_put_contents($vhostFilepath, $vhostContent);
        }
    }
}
