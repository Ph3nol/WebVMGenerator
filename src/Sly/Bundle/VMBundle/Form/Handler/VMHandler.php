<?php

namespace Sly\Bundle\VMBundle\Form\Handler;

use Sly\Bundle\VMBundle\Generator\Generator,
    Sly\Bundle\VMBundle\Entity\VM
;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Session\Session
;

use Doctrine\ORM\EntityManager;

/**
 * VM form handler.
 * 
 * @author Cédric Dugat <cedric@dugat.me>
 */
class VMHandler
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\Form\Form
     */
    private $form;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Sly\Bundle\VMBundle\Generator\Generator
     */
    private $generator;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   Request
     * @param \Symfony\Component\Form\Form              $form      Form
     * @param \Doctrine\ORM\EntityManager               $em        Entity manager
     * @param \Sly\Bundle\VMBundle\Generator\Generator  $generator Generator
     */
    public function __construct(Request $request, Form $form, EntityManager $em, Generator $generator)
    {
        $this->request   = $request;
        $this->form      = $form;
        $this->em        = $em;
        $this->generator = $generator;
    }

    /**
     * Process.
     *
     * @return boolean
     */
    public function process()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $vmData = $this->form->getData();

                $this->em->persist($vmData);
                $this->em->flush();

                $vm = $this->generator->generate($vmData);
            }

            return true;
        }

        return false;
    }
}
