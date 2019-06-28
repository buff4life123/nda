<?php
namespace App\Controller;

use App\Entity\PhotoService;
use App\Entity\Company;
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
use App\Service\ImageResizer;
use App\Service\Validations;
use App\Service\EnjoyApi;
use App\Service\Host;
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


    public function photoServiceAdd(Request $request, FileUploader $fileUploader,ImageResizer $imageResizer, Validations $validations)
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
                    //$imageResizer->resizeMultiple($fileName, $photoService->getFolder());

                    $em->persist($photoService);
                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'id' => $photoService->getId(),
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

    public function photoServiceZip(Request $request, FileUploader $fileUploader,  EnjoyApi $enjoyapi, Host $host)
    {
        $em = $this->getDoctrine()->getManager();
        $photoService = $em->getRepository(photoService::class)->find($request->request->get("id"));

        $filesystem = new Filesystem();
        $folderPath ='../public_html/upload/photo_service/';
        $publicResourcesFolderPath = $this->getParameter('kernel.project_dir') . '/public_html/upload/photo_service/' . $photoService->getFolder() . '/';
        $files = $this-> fileFinder($publicResourcesFolderPath);

        foreach($files as $file) {
            $pathZip[] = 'upload/photo_service/'.$photoService->getFolder() .'/'.$file;
        }

		$result = $fileUploader->createZip($pathZip, $folderPath.'/'.$photoService->getFolder().'.zip', false, $photoService->getFolder());
        
        if($result){
            if ($photoService->getLocales()->getId() == 1){
                $subject = "Photos";
                $msgLang = "to download your photos, please tap";
            } else {
                $subject = "Fotografias";
                $msgLang = "para baixar suas fotos, por favor clique";
            }

            $msg = $msgLang." https://nauticdrive-algarve.com/photo_service?c=".$photoService->getFolder()."e=".$photoService->getEmail();
            $msgSMS = str_replace(" ","+", $msg);

            //phone
            //$smsXML = $enjoyapi -> sendSMS($photoService->getTelephone(), $msgSMS);

            //email
            $company = $em->getRepository(Company::class)->find(1);
            $locale = $request->getlocale();

            $transport = (new \Swift_SmtpTransport($company->getEmailSmtp(), $company->getEmailPort(), $company->getEmailCertificade()))
                ->setUsername($company->getEmail())
                ->setPassword($company->getEmailPass());       

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message($subject))
                ->setFrom([$company->getEmail() => $company->getName()])
                ->setTo([$photoService->getEmail() => $photoService->getName(), $company->getEmail() => $company->getName()])
                ->addPart($subject, 'text/plain')
                ->setBody(
                    $this->renderView(
                        'emails/photoService-'.$photoService->getLocales()->getName().'.twig',
                        array(
                            'msg' => $msg,
                            'username' => $photoService->getName(),
                            'logo' => $host->getHost($request).'/upload/gallery/'.$company->getLogo(),
                            'company_name' => $company->getName()
                        )
                    ),
                'text/html'
            );

            //$mailer->send($message);

        }

        $response = array(
            'status' => $result,
        );

        $filesystem->remove(['../public_html/upload/photo_service/'.$photoService->getFolder()]);
        
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

    public function photoServiceSendEmail(Request $request, Host $host)
    {
        $em = $this->getDoctrine()->getManager();
        $photoService = $em->getRepository(photoService::class)->find($request->request->get("id"));

        if(!$photoService){

            $response = array(
                'status' => 0,
                'message' => 'fail',
                'data' => null
            );

            return new JsonResponse($response);
        }

        if ($photoService->getLocales()->getId() == 1){
            $subject = "Photos";
            $msgLang = "to download your photos, please tap";
        } else {
            $subject = "Fotografias";
            $msgLang = "para baixar suas fotos, por favor clique";
        }

        $msg = $msgLang." https://nauticdrive-algarve.com/photo_service?c=".$photoService->getFolder()."e=".$photoService->getEmail();

        //email
        $company = $em->getRepository(Company::class)->find(1);
        $locale = $request->getlocale();

        $transport = (new \Swift_SmtpTransport($company->getEmailSmtp(), $company->getEmailPort(), $company->getEmailCertificade()))
            ->setUsername($company->getEmail())
            ->setPassword($company->getEmailPass());       

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($subject))
            ->setFrom([$company->getEmail() => $company->getName()])
            ->setTo([$photoService->getEmail() => $photoService->getName(), $company->getEmail() => $company->getName()])
            ->addPart($subject, 'text/plain')
            ->setBody(
                $this->renderView(
                    'emails/photoService-'.$photoService->getLocales()->getName().'.twig',
                    array(
                        'msg' => $msg,
                        'username' => $photoService->getName(),
                        'logo' => $host->getHost($request).'/upload/gallery/'.$company->getLogo(),
                        'company_name' => $company->getName()
                    )
                ),
            'text/html'
        );

        $mailer->send($message);

        $response = array(
            'status' => 1,
            'message' => 'success',
            'data' => $photoService->getId(),
        );

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

}

?>