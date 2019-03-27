<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\CategoryType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;

class CategoryController extends AbstractController
{

    public function categoryNew(Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();
        
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        
        return $this->render('admin/category-new.html',array(
            'form' => $form->createView(),
            'locales' => $locales));
    }

    public function categoryAdd(Request $request, ValidatorInterface $validator)
    {
        $category = new Category();

        $s = json_decode($request->request->get('locale'));

        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

            if($form->isSubmitted()){
                
                if($form->isValid()){
                
                    $em = $this->getDoctrine()->getManager();

                try {

                    foreach ($s as $translated) {

                        $locales = $em->getRepository(Locales::class)->find($translated->id);
                        
                        $categoryTranslation = new CategoryTranslation();
                        
                        $categoryTranslation->setLocales($locales);
                        $categoryTranslation->setName($translated->name);
                        $categoryTranslation->setCategory($category);
                        $em->persist($categoryTranslation);
                    }
                    
                    $em->persist($category);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $category->getId(),
                        'form' => $request->request->get('locale'),
                    );
                    
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
                        'status' => 0,
                        'message' => 'fail',
                        'data' => $this->getErrorMessages($form)
                    );
                }
            }
            else
                $response = array(
                    'status' => 2,
                    'message' => 'fail not submitted',
                    'data' => '');
        return new JsonResponse($response);
    }


    public function categoryShowEdit(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        
        $locales = $em->getRepository(Locales::class)->findAll();

        $category = $em->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        if($category) {

            $t = array();

           foreach($category->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getId(),
                );
           }
            $b[] = array(
                'id' => $category->getId(),
                'is_active' => $category->getIsActive(),
                'locales_translated' => $t,
            );
        }
        return $this->render('admin/category-edit.html',array(
            'form' => $form->createView(),
            'category' => $category,
            'locales' => $locales
        ));
    }



    public function categoryEdit(Request $request, ValidatorInterface $validator)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
      
        $category = $em->getRepository(Category::class)->find($id);

        if(!$category){
        
            $response = array(
                'status' => 0,
                'message' => 'category not found',
                'categoryId' => $id);
            return new JsonResponse($response);
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
            
            if($form->isValid()){ 
            
                $category = $form->getData();

                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $locales = $em->getRepository(Locales::class)->find($translated->locale_id);

                        $categoryTranslation = $em->getRepository(CategoryTranslation::class)->findOneBy(['locales' => $locales, 'category'=> $category]);
                    
                        if(!$categoryTranslation){

                            $categoryTranslation = new CategoryTranslation();
                        
                            $categoryTranslation->setLocales($locales);
                            $categoryTranslation->setName($translated->name);
                            $categoryTranslation->setCategory($category);
                            $em->persist($categoryTranslation);
                        }
                        else{
                            $categoryTranslation->setName($translated->name);
                            $em->persist($categoryTranslation);
                        }
                    }

                    $em->persist($category);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $category->getId()
                    );
                    return new JsonResponse($response);
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
                return new JsonResponse($response);
            }
        }
        return new JsonResponse($response);
    }


    public function categoryList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository(Category::class)->findAll([],['orderBy' => 'ASC']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $b = array();
        
        foreach ($categories as $category) {

            $t = array();

           foreach($category->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getLocales()->getId(),
                );
           }
            $b[] = array(
                'id' => $category->getId(),
                'is_active' => $category->getIsActive(),
                'locales_translated' => $t,
            );
        }
   
        return $this->render('admin/category-list.html', array(
            'categories' => $b,
            'locales' => $locales
            ));
    }


    public function categoryDelete(Request $request)
    {
        $deleted = 1;
        $response = array();
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $category = $em->getRepository(Category::class)->find($id);
        
        if (!$category) {
            return new JsonResponse(array('status'=> 0, 'message' => 'Imagem do categoria #'.$id . ' não existe.'));
        }

        $em->remove($category);
        $em->flush();


        return new JsonResponse(array('status' => 1, 'message' => 'Imagem do Category foi apagada'));
    }


    protected function getErrorMessages(\Symfony\Component\Form\Form $form) 
    {
        $errors = array();
        $err = array();
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors [] = $this->getErrorMessages($child);
            }
        }

        foreach ($errors as $error) {
                $err [] = $error;
        }

        return $err;
    }
}

?>