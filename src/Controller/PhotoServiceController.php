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
use App\Service\Validations;
use App\Service\EnjoyApi;
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
        // $now = new \DateTime('now');
        // //INTERVAL IS SET TO 30 DAYS (after 30 days remove folders)
        // $interval = new \DateInterval('P2D');
        // $now->sub($interval);
        // $startDateTime = \DateTime::createFromFormat('U', ($now->format('U') ));
        // $startDateTime->format("Y-m-d H:i:s");

        // $em = $this->getDoctrine()->getManager();

        // $expiredPhotoService = $em->getRepository(PhotoService::class)->deleteExpiredFolders($startDateTime);
        // $filesystem = new Filesystem();
        // foreach($expiredPhotoService as $p){
        //     $filesystem->remove(['../public_html/upload/photo_service/'.$p->getFolder()]);
        //     $p->setFolder('');
        //     $em->persist($p);
        // }
        // $em->flush();
        // dd($expiredPhotoService);

        $photoService = new PhotoService();

        $em = $this->getDoctrine()->getManager();

        //$locales = $em->getRepository(Locales::class)->findAll();

        $form = $this->createForm(PhotoServiceType::class, $photoService);
        
        $form->handleRequest($request);

        return $this->render('admin/photo-service-new.html',array(
            'form' => $form->createView(),
            ));
    }


    public function photoServiceAdd(Request $request, FileUploader $fileUploader,ImageResizer $imageResizer, Validations $validations, EnjoyApi $enjoyapi)
    {
        $photoService = new PhotoService();

        $form = $this->createForm(PhotoServiceType::class, $photoService);

        $form->handleRequest($request);

        $noFakeEmails = $validations -> noFakeEmails($photoService->getEmail());
        $validatePhone = $validations -> validatePhone($photoService->getTelephone());

        if ($noFakeEmails){
            $response = array(
                'status' => 0,
                'message' => 'photo_service_email',
            ); 

            return new JsonResponse($response);
        }

        if ($validatePhone){
            $response = array(
                'status' => 0,
                'message' => 'photo_service_telephone',
            ); 

            return new JsonResponse($response);
        }


        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
                
            try {
                    $today = new \Datetime('now');
                    $dechex = dechex($today->format('U'));

                    $photoService->setCreatedDate($today);
                    $photoService->setFolder($dechex);

                    $filesystem = new Filesystem();
    
                    $filesystem->mkdir("../public_html/upload/photo_service/".$photoService->getFolder()); 
                    $uploadedFile = $form['imageFile']->getData();
                    $fileName = $fileUploader->uploads($uploadedFile, $photoService->getFolder());
                    $imageResizer->resizeMultiple($fileName, $photoService->getFolder());

                    $em->persist($photoService);
                    $em->flush();

                    $photoService->getLocales()->getId() == 1 
                    ? $smsLanguage = "To download your photos, please tap"
                    : $smsLanguage = "Para baixar suas fotos, por favor clique";

                    $sms = $smsLanguage."https://nauticdrive-algarve.com/photo_service?c=".$photoService->getFolder()."e=".$photoService->getEmail();
                    $sms = str_replace(" ","+", $sms);
                    $smsXML = $enjoyapi -> sendSMS($photoService->getTelephone(), $sms);

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $photoService->getId(),
                        'fileName' => $fileName,
                    );
                } catch(DBALException $e){
                    $a = array("Contate administrador sistema sobre: ".$e->getMessage());

                    $response = array(
                        'status' => 0,
                        'message' => 'fail',
                        'data' => $a
                    );
                }
        }else 
            $response = array(
                'status' => 2,
                'message' => 'fail not submitted',
                'data' => $this->getErrorMessages($form));

      
        return new JsonResponse($response);
    }

    public function photoServiceList(Request $request, ValidatorInterface $validator)
    {
        // $em               = $this->getDoctrine()->getManager();
        // $photoService     = $em->getRepository(PhotoService::class)->findAll();

        return $this->render('admin/list-photo-service.html', array(
            //'photoService' =>  $photoService,
            )
        );
    }

    public function photoServiceSearch(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $start = $request->query->get('startDate') ?  \DateTime::createFromFormat('d/m/Y', $request->query->get('startDate')) : null; 
        $end = $request->query->get('endDate') ? \DateTime::createFromFormat("d/m/Y", $request->query->get('endDate')) : null; 
        $email = $request->query->get('photo-service-email');
        $telephone = $request->query->get('photo-service-telephone');

        if ($start || $end){
            $photoService = $this->getDoctrine()->getManager()->getRepository(PhotoService::class)->filter($start, $end);

            if ($photoService){

                foreach ($photoService as $photoService) {
                    $seePhotoService[] =
                        array(
                        'name' => $photoService->getName(),
                        'email' => $photoService->getEmail(),
                        'telephone' => $photoService->getTelephone(),
                        'created_date' => $photoService->getCreatedDate()->format('d/m/Y'),
                        'folder' => $photoService->getFolder(),
                        'marketing' => $photoService->getMarketing(),
                        'gdpr' => $photoService->getGdpr(),
                        );
                }


                $counter = count($seePhotoService);
                
                if ($counter > 0 && $counter <= 1500)
                
                    $response = array(
                        'data' => $seePhotoService, 
                        'options' => $counter, 
                        );
                else 
                    $response = array(
                        'data' => '', 
                        'options' => $counter, 
                    );

            }
            else 
                $response = array(
                    'data' => '', 
                    'options' => 0, 
                    );
            }
            else if ($email) {
                $photoService = $em->getRepository(PhotoService::class)->findOneBy(['email' => $email]);

                if ($photoService) {
                    $seePhotoService[] =
                    array(
                    'name' => $photoService->getName(),
                    'email' => $photoService->getEmail(),
                    'telephone' => $photoService->getTelephone(),
                    'created_date' => $photoService->getCreatedDate()->format('d/m/Y'),
                    'folder' => $photoService->getFolder(),
                    'marketing' => $photoService->getMarketing(),
                    'gdpr' => $photoService->getGdpr(),
                    );

                    $response = array(
                        'data' => $seePhotoService, 
                        'options' => 1, 
                        );
                }
                else 
                $response = array(
                    'data' => '', 
                    'options' => 0, 
                );
            }
            else if ($telephone) {
                $photoService = $em->getRepository(PhotoService::class)->findOneBy(['telephone' => $telephone]);
                
                if ($photoService) {
                    $seePhotoService[] =
                    array(
                    'name' => $photoService->getName(),
                    'email' => $photoService->getEmail(),
                    'telephone' => $photoService->getTelephone(),
                    'created_date' => $photoService->getCreatedDate()->format('d/m/Y'),
                    'folder' => $photoService->getFolder(),
                    'marketing' => $photoService->getMarketing(),
                    'gdpr' => $photoService->getGdpr(),
                    );

                    $response = array(
                        'data' => $seePhotoService, 
                        'options' => 1, 
                        );
                }
                else 
                $response = array(
                    'data' => '', 
                    'options' => 0, 
                );
            }
            else 
                $response = array(
                    'data' => 'fields', 
                    'options' => 0, 
                );

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