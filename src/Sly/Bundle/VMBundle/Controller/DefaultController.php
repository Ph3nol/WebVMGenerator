<?php

namespace Sly\Bundle\VMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use Sly\Bundle\VMBundle\Entity\VM;

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
        $vmProcessed = $formHandler->process();
 
        if ($vmProcessed && $request->isXmlHttpRequest()) {
            $vmCreationSuccessContent = $this->renderView(
                'SlyVMBundle:Default/Block:createdMessage.html.twig', array(
                    'vm' => $vmProcessed,
                )
            );

            return new Response($vmCreationSuccessContent, 200);
        } elseif ($vmProcessed) {
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
     * @Route("/download/{uKey}-vm.tar", name="vm_download", requirements={ "key" = "\w+" })
     */
    public function downloadAction(VM $vm)
    {
        $request = $this->get('request');
        
        $vmGenerator = $this->get('sly_vm.generator');
        $vmGenerator->setVM($vm);

        $vmArchivePath = $vmGenerator->getArchivePath();

        if (file_exists($vmArchivePath)) {
            $response = new Response();
            $response
                ->setContent(file_get_contents($vmArchivePath))
                ->setStatusCode(200)
            ;

            $response->headers->set('Content-Type', 'application/force-download');
            $response->headers->set(
                'Content-Disposition',
                'attachment; filename="'.$vmGenerator->getArchiveFilename().'"'
            );
            
            $response->send();
        } else {
            throw new NotFoundHttpException('No VM found for this key or user');
        }
    }
}
