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

        $contacts = json_decode($request->request->get("contacts"));

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
        $fileName = $fileUploader->uploads($uploadedFiles, $photoService->getFolder()); 
        
        $translations = $this->noticeTranslation($translator, $photoService->getLocales()->getName());

        if($zipResult){
            $notice = $this->personalizedNotice('photoServiceAdd', $company, $photoService, $translations, $host);
            //$smsXML = $enjoyapi -> sendSMS($photoService->getTelephone(), $notice["sms"]);
            $this->sendEmail($locale, $company, $photoService, $notice["email"]);
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
        $translations = $this->noticeTranslation($translator, $photoService->getLocales()->getName());
        $notice = $this->personalizedNotice('photoServiceResendEmail',$company, $photoService, $translations, $host);
        
        
        $this->sendEmail($locale, $company, $photoService, $notice);

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
                                
        $translations = $this->noticeTranslation($translator, $photoService["locales"]);
        $notice = $this->personalizedNotice('photoServiceConfirmation', $company, $photoService, $translations, $host);
        //$smsXML = $enjoyapi -> sendSMS($photoService->getTelephone(), $notice["sms"]);
        $this->sendEmail($locale, $company, $photoService, $notice);

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

    private function personalizedNotice($action, $company, $photoService, $translations, $host)
    {
        if ($action == "photoServiceConfirmation"){

            $subject = $translations["confirm_photos"];
 
            $emailMessage = '<h4>'.$translations["hello"] .'&ensp;' 
                            . $photoService["name"] . '<br><br>'
                            .$translations["photos_coming_soon"]. '.<br><br>'
                            .$translations["team"] .'&ensp;' 
                            . $company->getName() .'.<br>
                            <img src='. $host.'/upload/gallery/'.$company->getLogo() .'  style=" width:80px;">
                            </h4>';
            $sms    = str_replace(" ","+", $translations["hello"] .',' .$translations["photos_coming_soon"]."." 
            . $company->getName());
        }
        else {

            $now = new \Date('now');
            $interval = new \DateInterval('P8D');
            $now->sub($interval);
            $expirationDate = \Date::createFromFormat('U', ($now->format('U') ));
            $expirationDate->format("d/m/Y");

            $domain  = $translations["domain"];
            $message = $translations["sms_download"];
            $subject = $translations["photos"];
            $tripAdvisorUrl = "https://www.tripadvisor".$domain."/UserReviewEdit-g189112-d13795619-Nauticdrive-Albufeira_Faro_District_Algarve.html";
            $PhotoServiceUrl = "https://www.nauticdrive-algarve.com/photo_service?c="
                                .$photoService->getFolder()."e=".$photoService->getEmail()."&local=".$photoService->getLocales()->getName()."";
            $emailDownloadUrl ='<a target="_blank" href="'.$PhotoServiceUrl.'">'.$translations["here"] .'</a>'; //$translator->trans('here',array(), 'messages', $local)
            
            $sms    = str_replace(" ","+", $message." ".$PhotoServiceUrl);
            $emailMessage =    '<h4>'.$translations["hello"] . $username . '<br><br>'
                        .$translations["we_send_photos"] . $expirationDate. '<br>
                        '.$emailDownloadUrl.'<br><br><br>'
                        .$translations["make_comment"].
                        '<a target="_blank" href="https://www.tripadvisor.com/UserReviewEdit-g189112-d13795619-Nauticdrive-Albufeira_Faro_District_Algarve.html">tripadvisor</a>.<br>
                        '.$translations["at_your_disposal"].'<br>'
                        .$translations["visit"].'<a target="_blank" href="https://www.nauticdrive-algarve.com">nauticdrive-algarve.com</a>.<br>
                        '.$translations["team"] . $company->getName() .'.<br>
                        <img src='. $host->getHost($request).'/upload/gallery/'.$company->getLogo() .'  style=" width:80px;">
                        </h4>';
        }

        $notice = []; 
        $notice [] = array($emailMessage,  $subject);

        return $notice = array(
            'sms'     => $sms,
            'email'   => $notice,
        );
    }

    private function noticeTranslation($translator, $locale)
    {   
        return array('hello' => $translator->trans('hello',array(), 'messages', $locale),
                     'we_send_photos' => $translator->trans('we_send_photos',array(), 'messages', $locale),
                     'make_comment' => $translator->trans('make_comment'),
                     'at_your_disposal' => $translator->trans('at_your_disposal',array(), 'messages', $locale),
                     'visit' => $translator->trans('visit',array(), 'messages', $locale),
                     'team' => $translator->trans('team',array(), 'messages', $locale),
                     'here' => $translator->trans('here',array(), 'messages', $locale),
                     'sms_download' => $translator->trans('sms_download',array(), 'messages', $locale),
                     'confirm_photos' => $translator->trans('confirm_photos',array(), 'messages', $locale),
                     'photos_coming_soon' => $translator->trans('photos_coming_soon',array(), 'messages', $locale),
            );
    }
    
    private function sendEmail($locale, $company, $photoService, $notice) {

            $userMail = array();

            if($photoService->getContacts()){
                foreach($photoService->getContacts() as $emailToUser){
                    array_push($userMail, $emailToUser->getEmail());

                }
            }

            $transport = (new \Swift_SmtpTransport($company->getEmailSmtp(), $company->getEmailPort(), $company->getEmailCertificade()))
                ->setUsername($company->getEmail())
                ->setPassword($company->getEmailPass());       

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message($notice["email"][0][1]))
                ->setFrom([$company->getEmail() => $company->getName()])
                ->setBcc($userMail)
                ->setTo([$photoService->getEmail() => $photoService->getName(), "roman.bajireanu@intouchbiz.com" => $company->getName()]) //$company->getEmail2() nauticdrive.fotos@gmail.com
                ->addPart($notice["email"][0][1], 'text/plain')
                ->setBody(
                    $this->renderView(
                        'emails/photoService-email.twig',array('email' => $notice["email"][0][0])
                    ),
                'text/html'
            );

            $mailer->send($message);
        }

}

?>