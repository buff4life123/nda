<?php
namespace App\Controller;

use App\Entity\Seo;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\SeoType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;

class SeoController extends AbstractController
{
    public function seo(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->request->get('id')){
            $seo = $em->getRepository(Seo::class)->find($request->request->get('id'));
            $form = $this->createForm(Seo::class, $seo);
        }
        else
        {
            $seo = $em->getRepository(Seo::class)->findAll();
            $form = $this->createForm(SeoType::class);

        } 
        
        $locales = $em->getRepository(Locales::class)->findAll();
   
        if ($request->isXmlHttpRequest() && $request->request->get($form->getName())) {

            $form->submit($request->request->get($form->getName()));
            
            if($form->isSubmitted()){

                $seo->setLocales($locales);

                if($form->isValid()){

                    $em = $this->getDoctrine()->getManager();
                    $seo = $form->getData();

                    $seo->setLocales($locales);

                    $em->persist($seo);
                    $em->flush();

                    $response = array(
                        'result' => 1,
                        'message' => 'success',
                        'data' => $seo->getId());
                }
                else{   
                    $response = array(
                        'result' => 0,
                        'message' => 'fail',
                        'is_ok' =>$form["locales"]->getData(), 
                        'data' => $this->getErrorMessages($form)
                    );
                }
            }
            else
                $response = array(
                    'result' => 2,
                    'message' => 'fail not submitted',
                    'data' => '');
                return new JsonResponse($response);
        }

        return $this->render('admin/seo.html',array(
            'form' => $form->createView(),
            'seos' => $seo,
            'locales' => $locales
        ));

        return $this->render('admin/seo.html');
    }


    public function seoEdit(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        
        $seo = $em->getRepository(Seo::class)->find($request->request->get('id'));

        $form = $this->createForm(SeoType::class, $seo);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
                
            if($form->isValid()){ 

            $seo = $form->getData();

                try {
                    $em->persist($seo);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'Sucesso',
                        'data' => 'O registo '.$seo->getId().' foi gravado.');
                } 
                catch(DBALException $e){

                    $a = array("Contate administrador sistema sobre: ".$e->getMessage());

                    $response = array(
                        'status' => 0,
                        'message' => 'fail',
                        'data' => $a);
                }
            }
            
            else{   
                $response = array(
                    'result' => 0,
                    'message' => 'fail',
                    'data' => $this->getErrorMessages($form)
                );
            }
        }
        return new JsonResponse($response);
    }


    public function seoDelete(Request $request){

        $response = array();
        $seoId = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $seo = $em->getRepository(Seo::class)->find($seo);
       
        if (!$seo) {
            $response = array('message'=>'fail', 'status' => 'Registo #'.$seo . ' não existe.');
        }
        else{
            $em->remove($seo);
            $em->flush();

            $response = array('message'=>'success', 'status' => $seoId);
        }
        return new JsonResponse($response);
    }


    public function menuShow(Request $request){

        $response = array();

        $em = $this->getDoctrine()->getManager(); 

        $locales = $em->getRepository(Locales::class)->findOneBy(['name' => $this->session->get('_locale')->getName()]);

        $seo = $em->getRepository(SeoData::class)->findOneBy(['locales' => $locales]);
       
        $response = !$seo ?
            array('status' => 0, 'message' => 'Registo não encontrado', 'data' => null)
            :
            array('status' => 1, 'message' => $seo->getName(), 'data' => $seo->getRgpdHtml());
        return new JsonResponse($response);
    }


    protected function getErrorMessages(\Symfony\Component\Form\Form $form) 
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors [] = $this->getErrorMessages($child);
            }
        }
        return $errors;
    }

}

?>