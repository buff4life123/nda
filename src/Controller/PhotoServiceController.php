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


    public function photoServiceAdd(Request $request, ValidatorInterface $validator, FileUploader $fileUploader,ImageResizer $imageResizer, Validations $validations)
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
        $em               = $this->getDoctrine()->getManager();
        $photoService     = $em->getRepository(PhotoService::class)->findAll();
        
        //dd($photoService);

        return $this->render('admin/list-photo-service.html', array(
            'photoService' =>  $photoService,
            )
        );
    }

    public function photoServiceSearch(Request $request, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $start = $request->query->get('startDate') ?  \DateTime::createFromFormat('d/m/Y', $request->query->get('startDate')) : null; 
        $end = $request->query->get('endDate') ? \DateTime::createFromFormat("d/m/Y", $request->query->get('endDate')) : null; 

        dd($start);
    //     $start = $start != null ? $start->format('Y-m-d') : null;
    //     $end = $end != null ? $end->format('Y-m-d') : null;

    // if ($start || $end){

    //     $canceled = 0;
    //     $pending = 0;
    //     $confirmed = 0;

    //     $booking = $this->getDoctrine()->getManager()->getRepository(Booking::class)->bookingFilter($start, $end);

    //     if ($booking){

    //         foreach ($booking as $bookings) {
            
    //             if ($bookings->getStatus() ==='canceled')
    //                 $canceled = $canceled+1;
    //             else if ($bookings->getStatus() ==='pending')
    //                 $pending = $pending+1;
    //             else if ($bookings->getStatus() ==='confirmed')
    //                 $confirmed = $confirmed+1;
                
    //             $client = $bookings->getClient();

    //             $seeBookings[] =
    //                 array(
    //                 'booking' => $bookings->getId(),
    //                 'adult' => $bookings->getAdult(),
    //                 'children' => $bookings->getChildren(),
    //                 'baby' => $bookings->getBaby(),
    //                 'status' => $bookings->getStatus(),
    //                 'date' => $bookings->getDateEvent()->format('d/m/Y'),
    //                 'hour' => $bookings->getTimeEvent()->format('H:i'),
    //                 'tour' => $bookings->getAvailable()->getProduct()->getNamePt(),
    //                 'notes' => $bookings->getNotes(),
    //                 'user_id' => $client->getId(),   
    //                 'username' => $client->getUsername(),
    //                 'address' => $client->getAddress(),
    //                 'email' => $client->getEmail(),          
    //                 'telephone' => $client->getTelephone(),
    //                 'total' => $moneyFormatter->format($bookings->getAmount()).'€',
    //                 'wp' => $client->getCvv() ? 1 : 0,
    //                 'posted_at' => $bookings->getPostedAt()->format('d/m/Y'),
    //                 );
    //         }


    //         $counter = count($seeBookings);
            
    //         if ($counter > 0 && $counter <= 1500)
            
    //             $response = array(
    //                 'data' => $seeBookings, 
    //                 'options' => $counter, 
    //                 'pending' => $pending, 
    //                 'confirmed' => $confirmed, 
    //                 'canceled' => $canceled);
    //         else 
    //             $response = array(
    //                 'data' => '', 
    //                 'options' => $counter, 
    //                 'pending' => '', 
    //                 'confirmed' => '', 
    //                 'canceled' => '');

    //     }
    //     else 
    //         $response = array(
    //             'data' => '', 
    //             'options' => 0, 
    //             'pending' => '', 
    //             'confirmed' => '', 
    //             'canceled' => '');
    //     }
    //     else 
    //         $response = array(
    //             'data' => 'fields', 
    //             'options' => 0, 
    //             'pending' => '', 
    //             'confirmed' => '', 
    //             'canceled' => '');

    //     return new JsonResponse($response);
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