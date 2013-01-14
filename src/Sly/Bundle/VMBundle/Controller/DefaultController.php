<?php

namespace Sly\Bundle\VMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/download/{key}/vagrant.tar", name="download")
     * @Template()
     */
    public function indexAction()
    {
        $form        = $this->get('sly_vm.form_vm');
        $request     = $this->get('request');
        $formHandler = $this->get('sly_vm.form_handler_vm');
 
        if ($formHandler->process()) {
        }

        return array(
            'form' => $form->createView(),
            'key'  => uniqid(),
        );
    }
}
