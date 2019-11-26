<?php
namespace App\Controller;

use App\Entity\PhotoService;
use App\Entity\PhotoServiceContacts;
use App\Entity\Company;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Finder\Finder;
use App\Form\PhotoServiceType;
use App\Service\FileUploader;
use App\Service\Validations;
use App\Service\EnjoyApi;
use App\Service\Host;
use App\Service\ImageResizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Translation\TranslatorInterface;


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

        $locales = $em->getRepository(Locales::class)->findAll();
    
        //$form = $this->createForm(PhotoServiceType::class, $photoService);
        // $form->handleRequest($request);

        return $this->render('admin/photo-service-new.html',array(
            //'form' => $form->createView(),
            'locales' => $locales,
            ));
    }


    public function photoServiceAdd(
        Request $request, 
        FileUploader $fileUploader, 
        Validations $validations, 
        ImageResizer $imageResizer, 
        EnjoyApi $enjoyapi, 
        Host $host, 
        TranslatorInterface $translator)
    { 
        $locale = $request->getlocale();
        $host = $host->getHost($request);

        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository(Company::class)->find(1);
        $locales = $em->getRepository(Locales::class)->find($request->request->get("locales"));

        $photoService = new PhotoService();

        $today = new \Datetime('now');
        $dechex = dechex($today->format('U'));

        $photoService->setCreatedDate($today);
        $photoService->setFolder($dechex);

        
        $photoService->setName($request->request->get("name"));
        $photoService->setEmail($request->request->get("email"));
        $photoService->setTelephone($request->request->get("telephone"));

        $contacts = $request->request->get("contacts");//json_decode();

        if($contacts){
            foreach ($contacts as $contact) {
                $photoServiceContacts = new PhotoServiceContacts();
                $photoServiceContacts->setEmail($contact);
                $photoServiceContacts->setPhotoService($photoService);
                $photoService->addContacts($photoServiceContacts);
                $em->persist($photoServiceContacts);
            }
        }

        $photoService->setLocales($locales);

        $em->persist($photoService);
        $em->flush();

        $filesystem = new Filesystem();
        $filesystem->mkdir($this->photo_service_directory.'/'.$photoService->getFolder());

        $folderPath = $this->photo_service_directory.'/'.$photoService->getFolder().'.zip';
        $uploadedFiles = $request->files->get("files");


        $zipResult = $fileUploader->createZip($uploadedFiles, $folderPath, false, $dechex);

       $fileUploader->uploads($uploadedFiles, $photoService->getFolder()); 
        
        $photosTranslation = $this->photosTranslation($translator, $photoService->getLocales()->getName());

        $PhotoServiceUrl = "https://www.nauticdrive-algarve.com/photo_service?c=".$photoService->getFolder()."e=".$photoService->getEmail()."&local=".$photoService->getLocales()->getName();
        
        if($zipResult){
            $sms    = str_replace(" ","+", $photosTranslation["sms_download"]." ".$PhotoServiceUrl);
            $smsXML = $enjoyapi -> sendSMS($photoService->getTelephone(), $sms);

            $this->sendEmail("photoServiceAdd", $locale, $company, $photoService, $photosTranslation, $host, $request);
        }

        $response = array(
            'status' => 1,
            'message' => 'success',
            'id' => $photoService->getId(),
            'zipResult' => $zipResult,
        );
        return new JsonResponse($response);
    }


    private function fileFinder($dir)
	{
		$icons_finder = new Finder();
		$icons_finder->files()->in($dir)->sortByName();
		$icons_name = array();
		foreach ($icons_finder as $name) {
			array_push($icons_name, basename($name));
		}

		return $icons_name;
	}

    public function photoServiceResendEmail(Request $request, Host $host, TranslatorInterface $translator)
    {
        $locale = $request->getlocale();
        $host = $host->getHost($request);

        $em = $this->getDoctrine()->getManager();
        $photoService = $em->getRepository(photoService::class)->find($request->request->get("id"));
        $company = $em->getRepository(Company::class)->find(1);

        if(!$photoService){

            $response = array(
                'status' => 0,
                'message' => 'fail',
                'data' => null
            );

            return new JsonResponse($response);
        }
        $photosTranslation = $this->photosTranslation($translator, $photoService->getLocales()->getName());
        
        $this->sendEmail("photoServiceResendEmail", $locale, $company, $photoService, $photosTranslation, $host, $request);

        $response = array(
            'status' => 1,
            'message' => 'success',
            'data' => $photoService->getId(),
        );

        return new JsonResponse($response);
    }

    public function photoServiceConfirmation(Request $request, Host $host, EnjoyApi $enjoyapi, TranslatorInterface $translator){

        $locale = $request->getlocale();
        $host = $host->getHost($request);
        
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository(Company::class)->find(1);

        $photoService = array("name"=> $request->request->get("name"),
                                "email"=> $request->request->get("email"),
                                "telephone"=> $request->request->get("telephone"),
                                "locales"=> $request->request->get("locales"),
                                "contacts"=> json_decode($request->request->get("contacts")),       
                                );
                                
        $local  = $photoService["locales"] == 1? "en":"pt";
        $photosTranslation = $this->photosTranslation($translator, $local);
    
        $sms    = str_replace(" ","+", $photosTranslation["photos_coming_soon"]);
        $smsXML = $enjoyapi -> sendSMS($photoService["telephone"], $sms);

        $this->sendEmail("photoServiceConfirmation", $locale, $company, $photoService, $photosTranslation, $host, $request);

        $response = array(
            'status' => 1,
            'message' => 'success',
        );
        return new JsonResponse($response);
        
    }


    public function photoServiceList(Request $request, ValidatorInterface $validator)
    {
        return $this->render('admin/list-photo-service.html', array(
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

        $photoService = '';

        if ($start || $end)
            $photoService = $em->getRepository(PhotoService::class)->filter($start, $end);
        else if ($email) 
            $photoService = $em->getRepository(PhotoService::class)->findBy(['email' => $email]);
        else if ($telephone) 
            $photoService = $em->getRepository(PhotoService::class)->findBy(['telephone' => $telephone]);


        if ($photoService) {
            
            if(is_array($photoService)){
                foreach($photoService as $item){
                    $seePhotoService[] =
                    array(
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'email' => $item->getEmail(),
                    'telephone' => $item->getTelephone(),
                    'created_date' => $item->getCreatedDate()->format('d/m/Y'),
                    'folder' => $item->getFolder(),
                    'marketing' => $item->getMarketing(),
                    'gdpr' => $item->getGdpr(),
                    );
                }
            }

            $response = array(
                'data' => $seePhotoService, 
                'options' => count($seePhotoService), 
                );
        }
        else 
        $response = array(
            'data' => '', 
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

    private function photosTranslation($translator, $locale)
    {   
        return array('hello' => $translator->trans('hello',array(), 'messages', $locale),
                     'we_send_photos' => $translator->trans('we_send_photos',array(), 'messages', $locale),
                     'make_comment' => $translator->trans('make_comment',array(), 'messages', $locale),
                     'at_your_disposal' => $translator->trans('at_your_disposal',array(), 'messages', $locale),
                     'visit' => $translator->trans('visit',array(), 'messages', $locale),
                     'team' => $translator->trans('team',array(), 'messages', $locale),
                     'here' => $translator->trans('here',array(), 'messages', $locale),
                     'sms_download' => $translator->trans('sms_download',array(), 'messages', $locale),
                     'confirm_photos' => $translator->trans('confirm_photos',array(), 'messages', $locale),
                     'photos_coming_soon' => $translator->trans('photos_coming_soon',array(), 'messages', $locale),
                     'domain' => $translator->trans('domain',array(), 'messages', $locale),
                     'photos' => $translator->trans('photos',array(), 'messages', $locale),
            );
    }
    
    private function sendEmail($action, $locale, $company, $photoService, $translations, $host, $request) {
            $userMail = array();
            $template = "";

            $contacts = $action == "photoServiceConfirmation"?$photoService["contacts"]:$photoService->getContacts();
            $userEmail = $action == "photoServiceConfirmation"?$photoService["email"]:$photoService->getEmail();
            $userName = $action == "photoServiceConfirmation"?$photoService["name"]:$photoService->getName();

            if ($action == "photoServiceConfirmation") {
                $template = "photosConfirmation";
                $userMail = $contacts;

            } else {
                $template = "photos";
                foreach($contacts as $emailToUser){
                    array_push($userMail, $emailToUser->getEmail());

                }
                
            }

            $transport = (new \Swift_SmtpTransport($company->getEmailSmtp(), $company->getEmailPort(), $company->getEmailCertificade()))
                ->setUsername($company->getEmail())
                ->setPassword($company->getEmailPass());       

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message($translations["photos"]))
                ->setFrom([$company->getEmail() => $company->getName()])
                ->setBcc($userMail)
                ->setTo([$userEmail => $userName, "nauticdrive.fotos@gmail.com" => $company->getName()]) //$company->getEmail2() nauticdrive.fotos@gmail.com roman.bajireanu@intouchbiz.com
                ->addPart( $translations["photos"], 'text/plain')
                ->setBody(
                    $this->renderView(
                        'emails/'.$template.'.twig',array('company' => $company,'photoService' => $photoService,'translations' => $translations,'logo' => $request->getHost().'/upload/gallery/'.$company->getLogo())
                    ),
                'text/html'
            );

            $mailer->send($message);
        }

}

?>