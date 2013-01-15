<?php

namespace Sly\Bundle\VMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $request     = $this->get('request');
        $form        = $this->get('sly_vm.form_vm');
        $formHandler = $this->get('sly_vm.form_handler_vm');
 
        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('vm_informations'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/vm-created.html", name="vm_informations")
     * @Template()
     */
    public function vmInformationsAction()
    {
        $session = $this->get('session');

        if ($session->has('generatorSessionID')) {
            return array();
        } else {
            throw new NotFoundHttpException('There is no VM recently created');
        }
    }

    /**
     * @Route("/download/my-vagrant-vm.tar")
     * @Route("/download/{key}-vm.tar", name="vm_download", requirements={ "key" = "\w+" })
     */
    public function downloadAction()
    {
        $request       = $this->get('request');
        $session       = $this->get('session');
        $vm            = $this->get('sly_vm.generator');
        $vmArchivePath = $vm->getArchivePath($request->attributes->get('key'));

        if (file_exists($vmArchivePath)) {
            $response = new Response();
            $response
                ->setContent(file_get_contents($vmArchivePath))
                ->setStatusCode(200)
            ;

            $response->headers->set('Content-Type', 'application/force-download');
            $response->headers->set('Content-Disposition', 'attachment; filename="'.$vm->getArchiveFilename().'"');
            
            $response->send();
        } else {
            throw new NotFoundHttpException('No VM found for this key or user');
        }
    }
}
