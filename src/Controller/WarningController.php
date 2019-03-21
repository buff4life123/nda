<?php
namespace App\Controller;

use App\Entity\Warning;
use App\Entity\WarningTranslation;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\WarningType;

class WarningController extends AbstractController
{
        public function adminWarning(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $warnings = $em->getRepository(Warning::class)->findAll();   
        $locales = $em->getRepository(Locales::class)->findAll();
       
        $t = array();

        foreach($warnings as $warning){

           foreach($warning->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'locale_id' => $translated->getLocales()->getId(),
                    'id' => $translated->getId()
                );
           }
            $w = array(
                'id' => $warning->getId(),
                'is_active' => $warning->getIsActive(),
                'locales_translated' => $t,
            );
        }

        if (!$warning) {
            throw $this->createNotFoundException('db no id 1 warning', array('%id%' => $id));
        }                        
        $form = $this->createForm(WarningType::class, $warning);

        return $this->render('admin/warning.html',array(
        'form' => $form->createView(),
        'locales' => $locales,
        'warning' => $w
        ));
    }



    public function adminWarningEdit(Request $request)
    {
       
        $em = $this->getDoctrine()->getManager();       

        $warning = $em->getRepository(Warning::class)->find($request->request->get('id'));

        if (!$warning) {
             $response = array(
                'status' => 0,
                'message' => 'fail',
                'data' => 'no id found');
            return new JsonResponse($response);
        }

        $form = $this->createForm(WarningType::class, $warning);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
               
                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $locales = $em->getRepository(Locales::class)->find($translated->locale_id);

                        $warningTranslation = $em->getRepository(WarningTranslation::class)->findOneBy(['locales' => $locales, 'warning' => $warning]);
                    
                        if(!$warningTranslation){
                        
                            $warningTranslation = new WarningTranslation();
                        
                            $warningTranslation->setLocales($locales);
                            $warningTranslation->setName($translated->name);
                            $warningTranslation->setWarning($warning);
                            $em->persist($warningTranslation);
                        }
                        else{
                            $warningTranslation->setName($translated->name);
                            $em->persist($warningTranslation);
                        }
                    }

                $warning = $form->getData();

                $em->persist($warning);
                $em->flush();
                $response = array(
                    'status' => 1,
                    'message' => 'success',
                    'data' => $warning->getId());

                } 
                catch(DBALException $e){
                    
                    $a = array("Contate administrador sistema sobre: ".$e->getMessage());
                    $response = array(
                        'status' => 0,
                        'message' => 'fail',
                        'data' => $a);
                    return new JsonResponse($response);
                }
            }
            else{   
                $response = array(
                    'status' => 0,
                    'message' => 'fail',
                    'data' => $this->getErrorMessages($form)
                );
            }
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