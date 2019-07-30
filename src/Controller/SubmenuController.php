<?php
namespace App\Controller;

use App\Entity\Menu;
use App\Entity\MenuTranslation;
use App\Entity\Submenu;
use App\Entity\SubmenuTranslation;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\SubmenuType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;

class SubmenuController extends AbstractController
{    

    public function submenuNew(Request $request)
    {
        $submenu = new Submenu();
        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();
        
        $form = $this->createForm(SubmenuType::class, $submenu);
        
        $form->handleRequest($request);


        $l = $request->getLocale() ? $request->getLocale() : 'pt_PT';

        $locale = $em->getRepository(Locales::class)->findOneBy(['name' => $l]);

        if(!$locale)
            $locale = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt_PT']);

        $menus = $em->getRepository(Menu::class)->findAll();
        $m = array();

        foreach ($menus as $menu)
        $m[] = array('id' => $menu->getId(), 'name' => $menu->getCurrentTranslation($locale));

        return $this->render('admin/submenu-new.html',array(
            'form' => $form->createView(),
            'menus' => $m,
            'locales' => $locales));
    }

    public function submenuAdd(Request $request)
    {
        $submenu = new Submenu();

        $s = json_decode($request->request->get('locale'));

        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Submenu::class)->findAll();



        $menu = $em->getRepository(Menu::class)->find($request->request->get('menu'));

        $form = $this->createForm(SubmenuType::class, $submenu);

        $form->handleRequest($request);

            if($form->isSubmitted()){
                
                if($form->isValid()){

                try {
        
                    foreach ($s as $translated) {
                        if ( isset($translated->id)){
                            $locales = $em->getRepository(Locales::class)->find($translated->id);
                            
                            $submenuTranslation = new SubmenuTranslation();
                            
                            $submenuTranslation->setLocales($locales);
                            $submenuTranslation->setName($translated->name);
                            $submenuTranslation->setSubmenu($submenu);
                            $em->persist($submenuTranslation);
                        }
                    }

                    $submenu->setOrderBy(count($totals)+1);
                    $submenu->setMenu($menu);
                    // $submenu->addMenu($menu);
                    $em->persist($submenu);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $submenu->getId(),
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


    public function submenuOrder(Request $request)
    {
        $result = $request->request->get('result');

        if (!$result)
        
           return new JsonResponse(array('status'=> 0, 'message' => 'nada para ordenar', 'data' => null));

        $order = json_decode($result);
    
        $em = $this->getDoctrine()->getManager();  

        foreach ($order as $orderBy) {
            $submenu = $em->getRepository(Submenu::class)->find($orderBy->id);
            $submenu->setOrderBy($orderBy->to);
            $em->persist($submenu);
            $em->flush();
        }

        $response = array('status'=> 1, 'message' => 'success', 'data' => count($order));
        
        return new JsonResponse($response);

    }

    public function submenuShowEdit(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        
        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Submenu::class)->findAll();

        $submenu = $em->getRepository(Submenu::class)->find($id);

        $form = $this->createForm(SubmenuType::class, $submenu);


        $l = $request->getLocale() ? $request->getLocale() : 'pt_PT';

        $locale = $em->getRepository(Locales::class)->findOneBy(['name' => $l]);

        if(!$locale)
            $locale = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt_PT']);
        // dd($submenu->getRoles());

        if($submenu) {

            $t = array();

            // $superUser  = $submenu->getRoles()[0] == "superuser"?"checked":false;
            // $admin      = $submenu->getRoles()[1] == "admin"?"checked":false;
            // $manager    = $submenu->getRoles()[2] == "manager"?"checked":false;

            // $menus = $em->getRepository(Menu::class)->findAll();
            // dd($menus);

            // $m = array();
            // foreach ($menus as $menu)
            //     $m[] = array('id' => $menu->getId(), 'name' => $menu->getCurrentTranslation($locale));

            $menus = $em->getRepository(Menu::class)->findAll();
            $m = array();
    
            foreach ($menus as $menu){
                $m[] = ['name'=>$menu->getCurrentTranslation($locale),'id'=>$menu->getId()];
            }

            //dd($m);
           foreach($submenu->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getId(),
                );
           }

            $b = array(
                'id' => $submenu->getId(),
                'active' => $submenu->getActive(),
                'order_by' => $submenu->getOrderBy(),
                'icon' => $submenu->getIcon(),
                'path' => $submenu->getPath(),
                // 'superuser' =>  $superUser,
                // 'admin' => $admin,
                // 'manager' => $manager,
                'locales_translated' => $t,
            );

             //dd($b);
        }
       

        return $this->render('admin/submenu-edit.html',array(
            'form' => $form->createView(),
            'submenu' => $submenu,
            'locales' => $locales,
            'totals' => count($totals),
            'b' => $b,
            'menus' => $m,
            'menu' => $submenu->getMenu()->getCurrentTranslation($locale),
            'menu_id' => $submenu->getId()
        ));
    }



    public function submenuEdit(Request $request, ValidatorInterface $validator)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
      
        $submenu = $em->getRepository(Submenu::class)->find($id);

        if(!$submenu){
        
            $response = array(
                'status' => 0,
                'message' => 'submenu not found',
                'menuid' => $id);
            return new JsonResponse($response);
        }

        $form = $this->createForm(SubmenuType::class, $submenu);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
            
            if($form->isValid()){ 
                
                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $locales = $em->getRepository(Locales::class)->find($translated->locale_id);

                        $submenuTranslation = $em->getRepository(SubmenuTranslation::class)->findOneBy(['locales' => $locales, 'submenu'=> $submenu]);

                        $menu = $em->getRepository(Menu::class)->find($request->request->get('menu'));
                        
                        $submenu->setMenu($menu);
                    
                        if(!$submenuTranslation){

                            $submenuTranslation = new SubmenuTranslation();
                        
                            $submenuTranslation->setLocales($locales);
                            $submenuTranslation->setName($translated->name);
                            $submenuTranslation->setMenu($submenu);
                            $em->persist($submenuTranslation);
                        }
                        else{
                            $submenuTranslation->setName($translated->name);
                            $em->persist($submenuTranslation);
                        }
                    }
                    // $roles = [];
                    // array_push($roles,  isset($request->request->get('submenu')['superuser']) ?"superuser":false);
                    // array_push($roles,  isset($request->request->get('submenu')['admin']) ?"admin":false);
                    // array_push($roles,  isset($request->request->get('submenu')['manager']) ?"manager":false);

                    // $submenu->setRoles($roles);

                    $submenu->setOrderBy($request->request->get('order_by'));
                    $em->persist($submenu);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $submenu->getId()
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


    public function submenuList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $submenus = $em->getRepository(Submenu::class)->findAll([],['orderBy' => 'ASC']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $b = array();
        
        $l = $request->getLocale() ? $request->getLocale() : 'pt_PT';

        $locale = $em->getRepository(Locales::class)->findOneBy(['name' => $l]);

        if(!$locale)
            $locale = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt_PT']);

        foreach ($submenus as $submenu) {
            
            $t = array();

           foreach($submenu->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getLocales()->getId(),
                );
           }
            $b[] = array(
                'id' => $submenu->getId(),
                'active' => $submenu->getActive(),
                'order_by' => $submenu->getOrderBy(),
                'locales_translated' => $t,
                'menu' => $submenu->getMenu()->getCurrentTranslation($locale)
            );
        }
   

        return $this->render('admin/submenu-list.html', array(
            'submenus' => $b,
            'locales' => $locales
            ));
    }


    public function submenuDelete(Request $request)
    {
        $deleted = 1;
        $response = array();
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $submenu = $em->getRepository(Submenu::class)->find($id);
        
        if (!$submenu) {
            return new JsonResponse(array('status'=> 0, 'message' => 'Submenu #'.$id . ' não existe.'));
        }

        $em->remove($submenu);
        $em->flush();

        return new JsonResponse(array('status' => 1, 'message' => 'Submenu foi apagado'));
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