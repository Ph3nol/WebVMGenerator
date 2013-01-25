<?php

namespace Sly\Bundle\VMBundle\Generator\PuppetElement;

/**
 * MailCatcher Puppet element.
 *
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\BasePuppetElement
 * @uses \Sly\Bundle\VMBundle\Generator\PuppetElement\PuppetElementInterface
 * @author Cédric Dugat <cedric@dugat.me>
 */
class MailCatcherElement extends BasePuppetElement implements PuppetElementInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mailCatcher';
    }

    /**
     * {@inheritDoc}
     */
    public function getCondition()
    {
        return (bool) $this->getVM()->getMailCatcher();
    }

    /**
     * {@inheritDoc}
     */
    public function getManifestLines()
    {
        return $this->getGenerator()->getTemplating()
            ->render('SlyVMBundle:VM/PuppetElement/Manifests:MailCatcherElement.html.twig', array(
                'vm' => $this->getVM(),
            ));
    }
}
