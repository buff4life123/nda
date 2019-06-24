<?php
namespace App\Controller;

use App\Entity\PhotoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\PhotoServiceType;
use App\Service\FileUploader;
use App\Service\ImageResizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;


class PhotoServiceController extends AbstractController
{
    private $photo_service_directory;
    
    public function __construct($photo_service_directory)
    {
        $this->photo_service_directory = $photo_service_directory;
    }

    public function photoServiceNew(Request $request)
    {
        $photoService = new PhotoService();

        $em = $this->getDoctrine()->getManager();

        //$locales = $em->getRepository(Locales::class)->findAll();

        $form = $this->createForm(PhotoServiceType::class, $photoService);
        
        $form->handleRequest($request);

        return $this->render('admin/photo-service-new.html',array(
            'form' => $form->createView(),
            ));
    }


    public function photoServiceAdd(Request $request, ValidatorInterface $validator, FileUploader $fileUploader,ImageResizer $imageResizer)
    {
        $photoService = new PhotoService();

        $form = $this->createForm(PhotoServiceType::class, $photoService);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                
            try {
                    $today = new \Datetime('now');
                    $dechex = dechex($today->format('U'));

                    $photoService->setCreatedDate($today);
                    $photoService->setFolder($dechex);

                    $filesystem = new Filesystem();
                    try {
                        $filesystem->mkdir("../public_html/upload/photo_service/".$dechex); //dd()
                        $uploadedFile = $form['imageFile']->getData();
                        $fileName = $fileUploader->uploads($uploadedFile, $dechex);
                        $imageResizer->resizeMultiple($fileName, $dechex);
                    } catch (IOExceptionInterface $exception) {
                        echo "An error occurred while creating your directory at ".$exception->getPath();
                    }

                    $em->persist($photoService);
                    $em->flush();

                    //dd(phpinfo());
                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $photoService->getId(),
                        'fileName' => $fileName,
                    );
                } 
                catch(DBALException $e)
                {
                    $a = array("Contate administrador sistema sobre: ".$e->getMessage());

                    $response = array(
                        'status' => 0,
                        'message' => 'fail',
                        'data' => $a
                    );
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

    public function galleryOrder(Request $request)
    {
        $result = $request->request->get('result');

        if (!$result)
        
           return new JsonResponse(array('status'=> 0, 'message' => 'nada para ordenar', 'data' => null));

        $order = json_decode($result);
    
        $em = $this->getDoctrine()->getManager();  

        foreach ($order as $orderBy) {
            $gallery = $em->getRepository(Gallery::class)->find($orderBy->id);
            $gallery->setOrderBy($orderBy->to);
            $em->persist($gallery);
            $em->flush();
        }

        $response = array('status'=> 1, 'message' => 'success', 'data' => count($order));
        
        return new JsonResponse($response);

    }





    public function galleryList(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $galleries = $em->getRepository(Gallery::class)->findAll([],['orderBy' => 'ASC']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $b = array();
        
        foreach ($galleries as $gallery) {

            $t = array();

           foreach($gallery->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getLocales()->getId(),
                );
           }
            $b[] = array(
                'id' => $gallery->getId(),
                'image' => $gallery->getImage(),
                'is_active' => $gallery->getIsActive(),
                'order_by' => $gallery->getOrderBy(),
                'locales_translated' => $t,
            );
        }

        return $this->render('admin/gallery-list.html',array(
            'galleries' =>  $b,
            'locales' => $locales));
    }



    public function galleryShowEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer)
    {
        
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        
        $locales = $em->getRepository(Locales::class)->findAll();

        $totals = $em->getRepository(Gallery::class)->findAll();

        $gallery = $em->getRepository(Gallery::class)->find($id);

        if ($gallery->getImage()) {

            $path = file_exists($this->photo_service_directory.'/'.$gallery->getImage()) ?
                $this->photo_service_directory.'/'.$gallery->getImage()
                :
                $this->photo_service_directory.'/no-image.png';

            $gallery->setImage(new File($path));
        }

        else
            $gallery->setImage(new File($this->photo_service_directory.'/no-image.png'));

        $form = $this->createForm(GalleryType::class, $gallery);

        if($gallery) {

            $t = array();

           foreach($gallery->getTranslation() as $translated){
                $t[] = array(
                    'local' => $translated->getLocales()->getName(),
                    'name' => $translated->getName(),
                    'local_id' => $translated->getId(),
                );
           }
            $b[] = array(
                'id' => $gallery->getId(),
                'image' => $gallery->getImage(),
                'is_active' => $gallery->getIsActive(),
                'order_by' => $gallery->getOrderBy(),
                'locales_translated' => $t,
            );
        }

        return $this->render('admin/gallery-edit.html',array(
            'form' => $form->createView(),
            'gallery' => $gallery,
            'locales' => $locales,
            'totals' => count($totals)
            ));
    }




    public function galleryEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer)
    {

        $id = $request->request->get('id');
        
        $em = $this->getDoctrine()->getManager();

        $gallery = $em->getRepository(Gallery::class)->find($id);

        $img = $gallery->getImage();

        if ($gallery->getImage()) {
            
            $path = file_exists($this->photo_service_directory.'/'.$gallery->getImage()) ?

            $this->photo_service_directory.'/'.$gallery->getImage()
            :
            $this->photo_service_directory.'/no-image.png';

            $gallery->setImage(new File($path));
        }

        else

            $gallery->setImage(new File($this->photo_service_directory.'/no-image.png'));

        $form = $this->createForm(GalleryType::class, $gallery);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
                
            if($form->isValid()){ 
                
                $deleted = 1;
                $gallery = $form->getData();
                $file = $gallery->getImage();

                if (is_object($file)) {
                    $fileName = $fileUploader->upload($file);               
                    $imageResizer->resize($fileName);
                    $gallery->setImage($fileName);
                    
                    //remove from folder older image

                    $fileSystem = new Filesystem();

                    if ($img && $img != 'no-image.png') {
                        try {
                            $fileSystem->remove($this->photo_service_directory.'/'.$img);
                        } 
                        catch (IOExceptionInterface $exception) {
                            $deleted = '0 '.$exception->getPath();
                        }
                    }
                }
                else
                    $gallery->setImage($img);

                try {

                    $t = json_decode($request->request->get('translated'));

                    foreach ($t as $translated) {
                    
                        $galleryTranslation = $em->getRepository(GalleryTranslation::class)->find($translated->id);
                    
                        if(!$galleryTranslation){

                            $locales = $em->getRepository(Locales::class)->find($translated->locale_id);
                        
                            $galleryTranslation = new galleryTranslation();
                        
                            $galleryTranslation->setLocales($locales);
                            $galleryTranslation->setName($translated->name);
                            $galleryTranslation->setGallery($gallery);
                            $em->persist($galleryTranslation);
                        }
                        else{
                            $galleryTranslation->setName($translated->name);
                            $em->persist($galleryTranslation);
                        }
                    }


                    $gallery->setOrderBy($request->request->get('order_by'));
                    $em->persist($gallery);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'image' => $deleted,
                        'data' => $gallery->getId());
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
        
        return new JsonResponse($response);
    }

    public function galleryDelete(Request $request)
    {
        $deleted = 1;
        $response = array();
        $galleryId = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();

        $gallery = $em->getRepository(Gallery::class)->find($galleryId);
       
        if (!$gallery) {
            $response = array('status' => 0, 'message' => 'Galeria #'.$galleryId .' não existe.', 'data' => null);
        }
        else{
            $img = $gallery->getImage();
            $em->remove($gallery);
            $em->flush();

            //remove from folder image

            $fileSystem = new Filesystem();

            if ($img && $img != 'no-image.png') {
                try {
                    $fileSystem->remove($this->photo_service_directory.'/'.$img);
                } 
                catch (IOExceptionInterface $exception) {
                    $deleted = '0 '.$exception->getPath();
                }
            }
            
            $response = array('status'=> 1, 'data' => $deleted, 'message' => $galleryId);
        }
        return new JsonResponse($response);
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