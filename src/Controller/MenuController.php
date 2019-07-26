<?php
namespace App\Controller;

use App\Entity\Menu;
use App\Entity\MenuTranslation;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\MenuType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;

class MenuController extends AbstractController
{    

    public function menuNew(Request $request)
    {
        $menu = new Menu();
        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();
        
        $form = $this->createForm(MenuType::class, $menu);
        
        $form->handleRequest($request);
        
        return $this->render('admin/menu-new.html',array(
            'form' => $form->createView(),
            'locales' => $locales));
    }

    public function menuAdd(Request $request, ValidatorInterface $validator)
    {
        $menu = new Menu();

        $s = json_decode($request->request->get('locale'));

        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Menu::class)->findAll();

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

            if($form->isSubmitted()){
                
                if($form->isValid()){

                try {

                    foreach ($s as $translated) {

                        $locales = $em->getRepository(Locales::class)->find($translated->id);
                        
                        $menuTranslation = new MenuTranslation();
                        
                        $menuTranslation->setLocales($locales);
                        $menuTranslation->setName($translated->name);
                        $menuTranslation->setMenu($menu);
                        $em->persist($menuTranslation);
                    }
                    
                    $menu->setOrderBy(count($totals)+1);
                    $em->persist($menu);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $menu->getId(),
                        'form' => $request->request->get('locale'),
                        'ff' => $s[0]->id
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


    public function menuOrder(Request $request)
    {
        $result = $request->request->get('result');

        if (!$result)
        
           return new JsonResponse(array('status'=> 0, 'message' => 'nada para ordenar', 'data' => null));

        $order = json_decode($result);
    
        $em = $this->getDoctrine()->getManager();  

        foreach ($order as $orderBy) {
            $menu = $em->getRepository(Menu::class)->find($orderBy->id);
            $menu->setOrderBy($orderBy->to);
            $em->persist($menu);
            $em->flush();
        }

        $response = array('status'=> 1, 'message' => 'success', 'data' => count($order));
        
        return new JsonResponse($response);

    }

    public function menuShowEdit(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        
        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Menu::class)->findAll();

        $menu = $em->getRepository(Menu::class)->find($id);

        $form = $this->createForm(MenuType::class, $menu);

        if($menu) {

            $t = array();

           foreach($menu->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getId(),
                );
           }
            $b[] = array(
                'id' => $menu->getId(),
                'active' => $menu->getActive(),
                'order_by' => $menu->getOrderBy(),
                'icon' => $menu->geIcon(),
                'path' => $menu->getPath(),
                'locales_translated' => $t,
            );
        }
       

        return $this->render('admin/menu-edit.html',array(
            'form' => $form->createView(),
            'menu' => $menu,
            'locales' => $locales,
            'totals' => count($totals)
        ));
    }



    public function menuEdit(Request $request, ValidatorInterface $validator)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
      
        $menu = $em->getRepository(Menu::class)->find($id);

        if(!$menu){
        
            $response = array(
                'status' => 0,
                'message' => 'menu not found',
                'menuid' => $id);
            return new JsonResponse($response);
        }

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
            
            if($form->isValid()){ 
                
                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $locales = $em->getRepository(Locales::class)->find($translated->locale_id);

                        $menuTranslation = $em->getRepository(MenuTranslation::class)->findOneBy(['locales' => $locales, 'menu'=> $menu]);
                    
                        if(!$menuTranslation){

                            $menuTranslation = new MenuTranslation();
                        
                            $menuTranslation->setLocales($locales);
                            $menuTranslation->setName($translated->name);
                            $menuTranslation->setMenu($menu);
                            $em->persist($menuTranslation);
                        }
                        else{
                            $menuTranslation->setName($translated->name);
                            $em->persist($menuTranslation);
                        }
                    }

                    $menu->setOrderBy($request->request->get('order_by'));
                    $em->persist($menu);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $menu->getId()
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


    public function menuList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository(Menu::class)->findAll([],['orderBy' => 'ASC']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $b = array();
        
        foreach ($menus as $menu) {

            $t = array();

           foreach($menu->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getLocales()->getId(),
                );
           }
            $b[] = array(
                'id' => $menu->getId(),
                'active' => $menu->getActive(),
                'order_by' => $menu->getOrderBy(),
                'locales_translated' => $t,
            );
        }
   
        return $this->render('admin/menu-list.html', array(
            'menus' => $b,
            'locales' => $locales
            ));
    }


    public function menuDelete(Request $request)
    {
        $deleted = 1;
        $response = array();
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $menu = $em->getRepository(Menu::class)->find($id);
        
        if (!$menu) {
            return new JsonResponse(array('status'=> 0, 'message' => 'Menu #'.$id . ' não existe.'));
        }

        $em->remove($menu);
        $em->flush();

        return new JsonResponse(array('status' => 1, 'message' => 'Menu foi apagado'));
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