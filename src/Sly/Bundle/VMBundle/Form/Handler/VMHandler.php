<?php

namespace Sly\Bundle\VMBundle\Form\Handler;

use Sly\Bundle\VMBundle\Generator\Generator;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Session\Session
;

use Doctrine\Common\Util\Inflector;

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
     * @var \Sly\Bundle\VMBundle\Generator\Generator
     */
    private $generator;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   Request
     * @param \Symfony\Component\Form\Form              $form      Form
     * @param \Sly\Bundle\VMBundle\Generator\Generator  $generator Generator
     */
    public function __construct(Request $request, Form $form, Generator $generator)
    {
        $this->request   = $request;
        $this->form      = $form;
        $this->generator = $generator;
    }

    /**
     * Process.
     */
    public function process()
    {
        if ('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                $dataBag = $this->getDataBag($this->form->getData());

                $this->generator->updateVMConfig($dataBag);

                $vm = $this->generator->generate();

                /**
                 * Test with Archive_Tar PEAR package.
                 *
                 * @todo Fix it.
                 */
                $vmArchive = new \Archive_Tar(
                    sprintf(
                        '%s/%s.tar',
                        $this->generator->getCachePath(),
                        Inflector::tableize($vm['configuration']['name'])
                    )
                );


                $vmArchive->setErrorHandling(PEAR_ERROR_PRINT);  
                $vmArchiveContent = array($this->generator->getCachePath());
                $vmArchive->create($vmArchiveContent);
            }

            return true;
        }

        return false;
    }

    /**
     * Get data bag from form data.
     * 
     * @param array $formData Form data
     * 
     * @return array
     */
    private function getDataBag(array $formData = array())
    {
        $dataBag = array();

        foreach ($formData as $key => $value) {
            $data = explode('_', $key);

            if (2 == count($data)) {
                $dataBag[$data[0]][$data[1]] = $value;
            } else {
                $dataBag[$data[0]] = $value;
            }
        }

        return $dataBag;
    }
}
