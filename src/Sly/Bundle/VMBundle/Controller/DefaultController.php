<?php

namespace Sly\Bundle\VMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use Sly\Bundle\VMBundle\Config\Config,
    Sly\Bundle\VMBundle\Entity\VM
;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $form    = $this->get('sly_vm.form_vm');

        if ($request->query->has('c')) {
            $em = $this->getDoctrine()->getEntityManager();

            $vm = $em
                ->getRepository('SlyVMBundle:VM')
                ->findOneByUKey($request->query->get('c'))
            ;

            if ($vm) {
                $form->setData($vm);
            }
        }

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
     * @Route("/download/{uKey}.tar", name="vm_download_archive", requirements={"key" = "\w+"})
     */
    public function downloadAction(VM $vm)
    {
        $kernelRootDir = $this->container->getParameter('kernel.root_dir');
        $request       = $this->get('request');
        $vmGenerator   = $this->get('sly_vm.generator');
        
        $vmGenerator->generate($vm);

        $response = new Response();
        $response
            ->setContent(file_get_contents($vm->getArchivePath($kernelRootDir)))
            ->setStatusCode(200)
        ;

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="'.$vm->getArchiveFilename().'"'
        );
        
        return $response;
    }

    /**
     * @Route("/download/{uKey}.sh", name="vm_download_install", requirements={"key" = "\w+"})
     */
    public function downloadInstallScriptAction(VM $vm)
    {
        $kernelRootDir = $this->container->getParameter('kernel.root_dir');
        $request       = $this->get('request');
        $vmGenerator   = $this->get('sly_vm.generator');
        $gitModules    = array();

        $vmGenerator->setVM($vm);

        foreach ($vmGenerator->getPuppetElements() as $puppetElement) {
            $puppetElement->setGenerator($vmGenerator);

            if ($puppetElement->getCondition() && (bool) count($puppetElement->getGitModules())) {
                foreach ($puppetElement->getGitModules() as $gitModule) {
                    $gitModules[$gitModule[0]] = $gitModule[1];
                }
            }
        }

        $installContent = $this->renderView('SlyVMBundle:VM:install.html.twig', array(
            'vm'         => $vm,
            'gitModules' => $gitModules,
        ));

        $response = new Response();
        $response
            ->setContent($installContent)
            ->setStatusCode(200)
        ;

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="webvmgen-install.sh"'
        );
        
        return $response;
    }

    /**
     * @Route("/vm-configs-components", name="vm_configs_component")
     * @Template("SlyVMBundle:Default/Block:vmConfigsComponent.html.twig")
     */
    public function vmConfigsComponentAction()
    {
        $config         = $this->container->getParameter('sly_vm.configuration');
        $configurations = $config['configurations'];
        $vmConfigs      = array();

        foreach ($configurations as $k => $c) {
            $vmConfigs[$k] = array_merge(Config::getVMDefaultConfig(), $c);
        }

        return array('configs' => json_encode($vmConfigs));
    }
}
