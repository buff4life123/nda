<?php
namespace App\Controller;

use App\Entity\Banner;
use App\Entity\BannerTranslation;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\BannerType;
use App\Service\FileUploader;
use App\Service\ImageResizer;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;

class BannerController extends AbstractController
{

    private $banner_images_directory;

    public function __construct($banner_images_directory)
    {
        $this->banner_images_directory = $banner_images_directory;
    }
    

    public function bannerNew(Request $request)
    {
        $banner = new Banner();
        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();
        
        $form = $this->createForm(BannerType::class, $banner);
        
        $form->handleRequest($request);
        
        return $this->render('admin/banner-new.html',array(
            'form' => $form->createView(),
            'locales' => $locales));
    }

    public function bannerAdd(Request $request, ValidatorInterface $validator, FileUploader $fileUploader,ImageResizer $imageResizer)
    {
        $banner = new Banner();

        $s = json_decode($request->request->get('locale'));

        $em = $this->getDoctrine()->getManager();

        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Banner::class)->findAll();

        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);

            if($form->isSubmitted()){
                
                if($form->isValid()){

                    $em = $this->getDoctrine()->getManager();

                    $file = $banner->getImage();

                    if ($file) {
                        $fileName = $fileUploader->upload($file);               
                        $imageResizer->resize($fileName);
                        $banner->setImage($fileName);
                    }
                    else{
                        $banner->setImage($this->banner_images_directory.'/no-image.png');
                    }

                try {

                    foreach ($s as $translated) {

                        $locales = $em->getRepository(Locales::class)->find($translated->id);
                        
                        $bannerTranslation = new BannerTranslation();
                        
                        $bannerTranslation->setLocales($locales);
                        $bannerTranslation->setName($translated->name);
                        $bannerTranslation->setBanner($banner);
                        $em->persist($bannerTranslation);
                    }
                    
                    $banner->setOrderBy(count($totals)+1);
                    $em->persist($banner);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $banner->getId(),
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


    public function bannerOrder(Request $request)
    {
        $result = $request->request->get('result');

        if (!$result)
        
           return new JsonResponse(array('status'=> 0, 'message' => 'nada para ordenar', 'data' => null));

        $order = json_decode($result);
    
        $em = $this->getDoctrine()->getManager();  

        foreach ($order as $orderBy) {
            $banner = $em->getRepository(Banner::class)->find($orderBy->id);
            $banner->setOrderBy($orderBy->to);
            $em->persist($banner);
            $em->flush();
        }

        $response = array('status'=> 1, 'message' => 'success', 'data' => count($order));
        
        return new JsonResponse($response);

    }

    public function bannerShowEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        
        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Banner::class)->findAll();

        $banner = $em->getRepository(Banner::class)->find($id);

        if ($banner->getImage()) {

            $path = file_exists($this->banner_images_directory.'/'.$banner->getImage()) ?
                $this->banner_images_directory.'/'.$banner->getImage()
                :
                $this->banner_images_directory.'/no-image.png';

            $banner->setImage(new File($path));
        }

        else
            $banner->setImage(new File($this->gallery_images_directory.'/no-image.png'));

        $form = $this->createForm(BannerType::class, $banner);

        if($banner) {

            $t = array();

           foreach($banner->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getId(),
                );
           }
            $b[] = array(
                'id' => $banner->getId(),
                'image' => $banner->getImage(),
                'is_active' => $banner->getIsActive(),
                'text_active' => $banner->getTextActive(),
                'order_by' => $banner->getOrderBy(),
                'locales_translated' => $t,
            );
        }
       

        return $this->render('admin/banner-edit.html',array(
            'form' => $form->createView(),
            'banner' => $banner,
            'locales' => $locales,
            'totals' => count($totals)
        ));
    }



    public function bannerEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer)
    {
        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
      
        $banner = $em->getRepository(Banner::class)->find($id);

        if(!$banner){
        
            $response = array(
                'status' => 0,
                'message' => 'banner not found',
                'bannerid' => $id);
            return new JsonResponse($response);
        }

        $img = $banner->getImage();


        if ($banner->getImage()) {
            
            $path = file_exists($this->banner_images_directory.'/'.$banner->getImage()) ?

            $this->banner_images_directory.'/'.$banner->getImage()
            :
            $this->banner_images_directory.'/no-image.png';

            $banner->setImage(new File($path));          

        }

        else

            $banner->setImage(new File($this->banner_images_directory.'/no-image.png'));

        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
            
            if($form->isValid()){ 
                
                $deleted = 1;
                $banner = $form->getData();
                $file = $banner->getImage();

                if (is_object($file)) {
                    $fileName = $fileUploader->upload($file);               
                    $imageResizer->resize($fileName);
                    $banner->setImage($fileName);
                    
                    //remove from folder older image

                    $fileSystem = new Filesystem();

                    if ($img && $img != 'no-image.png') {
                        try {
                            $fileSystem->remove($this->banner_images_directory.'/'.$img);
                        } 
                        catch (IOExceptionInterface $exception) {
                            $deleted = '0 '.$exception->getPath();                          
                            $response = array(
                                'status' => 0,
                                'message' => $deleted,
                                'bannerid' => $id);

                            return new JsonResponse($response);

                        }
                    }
                }
                else
                    $banner->setImage($img);
                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $bannerTranslation = $em->getRepository(BannerTranslation::class)->find($translated->id);
                    
                        if(!$bannerTranslation){

                            $locales = $em->getRepository(Locales::class)->find($translated->locale_id);
                        
                            $bannerTranslation = new BannerTranslation();
                        
                            $bannerTranslation->setLocales($locales);
                            $bannerTranslation->setName($translated->name);
                            $bannerTranslation->setBanner($banner);
                            $em->persist($bannerTranslation);
                        }
                        else{
                            $bannerTranslation->setName($translated->name);
                            $em->persist($bannerTranslation);
                        }
                    }


                    $banner->setOrderBy($request->request->get('order_by'));
                    $em->persist($banner);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'image' => $deleted,
                        'data' => $banner->getId()
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


    public function bannerList(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $banners = $em->getRepository(Banner::class)->findAll([],['orderBy' => 'ASC']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $b = array();
        
        foreach ($banners as $banner) {

            $t = array();

           foreach($banner->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getLocales()->getId(),
                );
           }
            $b[] = array(
                'id' => $banner->getId(),
                'image' => $banner->getImage(),
                'is_active' => $banner->getIsActive(),
                'text_active' => $banner->getTextActive(),
                'order_by' => $banner->getOrderBy(),
                'locales_translated' => $t,
            );
        }
   
        return $this->render('admin/banner-list.html', array(
            'banners' => $b,
            'locales' => $locales
            ));
    }


    public function bannerDelete(Request $request)
    {
        $deleted = 1;
        $response = array();
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $banner = $em->getRepository(Banner::class)->find($id);
        
        if (!$banner) {
            return new JsonResponse(array('status'=> 0, 'message' => 'Imagem do banner #'.$id . ' não existe.'));
        }
        
        $img = $banner->getImage();

        $em->remove($banner);
        $em->flush();

        //remove from folder image

        $fileSystem = new Filesystem();

        if ($img && $img != 'no-image.png') {
            try {
                $fileSystem->remove($this->banner_images_directory.'/'.$img);
            } 
            catch (IOExceptionInterface $exception) {
                $deleted = '0 '.$exception->getPath();
            }
        }
        return new JsonResponse(array('status' => 1, 'message' => 'Imagem do Banner foi apagada'));
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