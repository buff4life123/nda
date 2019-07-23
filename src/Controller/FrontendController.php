<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use App\Entity\PhotoService;
use App\Entity\AboutUs;
use App\Entity\Rgpd;
use App\Entity\TermsConditions;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use EmailValidator\EmailValidator;
use Symfony\Component\Finder\Finder;
use App\Service\ExperienceApi;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class FrontendController extends AbstractController
{
	private $session;
	private $appKernel;

	public function __construct(SessionInterface $session, KernelInterface $appKernel)
	{
	   $this->session = $session;
	   $this->appKernel = $appKernel;
	}

    public function home(Request $request, ExperienceApi $experience)
    {
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		$products = $experience->getProducts($request->getLocale());

		$rgpd = $em->getRepository(Rgpd::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);
		$terms_conditions = $em->getRepository(TermsConditions::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);

		$social_network_icons = $this-> fileFinder("../public_html/images/icons");
		$header_slider_items  = $this-> fileFinder("../public_html/images/headerSlider");
		$locales = $this-> defaultUserLocale($request);
		$local = $locales->getName() == "pt_PT"?"pt":"en";

		return $this->render('index/index.html.twig',  array(
			'page' => 'index', 
			'company' => $company,
			'rgpd' => $rgpd,
			'terms_conditions' => $terms_conditions,
			'social_network_icons' => $social_network_icons,
			'header_slider_items'  => $header_slider_items,
			'products' => $products['products'],
			'exp_api_key' => $products['key'],
			'url_api_key' => $products['url'],
			'local' => $local,
		
		));
			
	}

    public function aboutUs(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		$about = $em->getRepository(AboutUs::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);

		return $this->render('index/about_us.html.twig',  array(
			'page' => 'about_us',
			'company' => $company,
			// 't' => $request->getLocale(),
			'about' => $about,
		));
	}
	
	public function otherCompany(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		$about = $em->getRepository(AboutUs::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);

		return $this->render('index/other_company.twig',  array(
			'page' => 'about_us',
			'company' => $company,
			// 't' => $request->getLocale(),
			'about' => $about,
		));
	}
	
	public function photoService(Request $request, TranslatorInterface $translator)
	{	
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);

		$rgpd = $em->getRepository(Rgpd::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);
		$terms_conditions = $em->getRepository(TermsConditions::class)->findOneBy(['locales' => $this-> defaultUserLocale($request)]);

		$code = null;
		$email= null;
		$local= null;

		if($request->query->get("c")){
			$param = explode('e=',$request->query->get("c"));

			if (count($param) > 1){
				$code = $param[0];
				$email = $param[1];
				$local = $request->query->get("local") ? $request->query->get("local") :'pt_PT';

				if($this-> defaultUserLocale($request)->getName() != $local){
					$this->session->set('_locale', $local);
					$translator->setLocale($local);
				}
			}
		}

		return $this->render('index/photo_service.twig',  array(
			//'page' => 'about_us',
			'company' => $company,
			'rgpd' => $rgpd,
			'terms_conditions' => $terms_conditions,
			'email' => $email,
			'code' => $code,
			// 't' => $request->getLocale(),
			//'about' => $about,
		));
	}

	public function photoServicePreview(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);

		$code  = $request->request->get("photo-service-code");
		$email = $request->request->get("photo-service-email");
		$marketing = $request->request->get("marketing-agree");

		$photoService = $em->getRepository(PhotoService::class)->findOneBy(['folder' => $code,'email' => $email]);

		//dd($marketing);
		if($photoService) {
			$folder = $photoService->getFolder();
			$id = $photoService->getId();
			
			$publicResourcesFolderPath = $this->appKernel->getProjectDir(). '/public_html/upload/photo_service/' . $folder . '/';
			$files = $this-> fileFinder($publicResourcesFolderPath);

			$images = array();
			foreach ($files as $file) {	
			 	$images[] = "/upload/photo_service/" . $folder . "/" . $file;
			}

			$response = array(
				'status' => 1,
				'images' => $images);
				
			$photoService->setGdpr(1);
			$photoService->setMarketing($marketing);
			$em->persist($photoService);
			$em->flush();
		}else
		{
			$response = array(
				'status' => 0
				);
		}
		
		return new JsonResponse($response);	
    }
	
	public function photoServiceDownload(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);

		$code  = $request->request->get("photo-service-code");
		$email = $request->request->get("photo-service-email");
		$marketing = $request->request->get("marketing-agree");

		$photoService = $em->getRepository(PhotoService::class)->findOneBy(['folder' => $code,'email' => $email]);

		//dd($marketing);
		if($photoService) {
			$folder = $photoService->getFolder();
			$id = $photoService->getId();
			$path = "/upload/photo_service/";
			// $path = $this->appKernel->getProjectDir().'/public_html/upload/photo_service/';
			// $isExtracted = $fileUploader->extractTo($path,$path.$folder.'.zip');
			// dd($isExtracted);
			
			// $publicResourcesFolderPath = $this->getParameter('kernel.project_dir') . '/public_html/upload/photo_service/' . $folder . '/';

			// $files = $this-> fileFinder($publicResourcesFolderPath);

			// $images = array();
			// foreach ($files as $file) {	
			// 	$images[] = "/upload/photo_service/" . $folder . "/" . $file;
			// }

			$response = array(
				'status' => 1,
				'folder' => $path.$folder.'.zip');
				
			$photoService->setGdpr(1);
			$photoService->setMarketing($marketing);
			$em->persist($photoService);
			$em->flush();
		}else
		{
			$response = array(
				'status' => 0
				);
		}
		
		return new JsonResponse($response);	
    }

	function activity($id, $text, Request $request,  ExperienceApi $experience)
	{
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		$products = $experience->getProduct($request->getLocale(), $id);
		

    	return $this->render('index/activity.html.twig', array(
			'page'=> 'activity',
			'company' => $company,
			'products' => $products['products'],
			'exp_api_key' => $products['key'],
			'url_api_key' => $products['url']));

	}



	public function pageDetail(Request $request, $page, $pageSub, TranslatorInterface $translator)
    {
		$request = $request->getlocale();
		
		$pageInfo = array();
		$pageInfo = array('title'=> $translator->trans('home.title'), 'sub_title' => $translator->trans('home.sub_title'), 'text' => $translator->trans('home.text'));
		
		$pageDesc = array();
		if($pageSub == 'about_us' || $pageSub == 'contacts')
		{
			$pageDesc = array(
				'title' => $translator->trans('page_detail.'.$pageSub.'.title'),
				'sub_title' => $translator->trans('page_detail.'.$pageSub.'.sub_title'),
				'text_list' => array()
			);
			for($i = 0; $i < 15; $i++)
			{
				$locale = $translator->getLocale();
				$catalogue = $translator->getCatalogue($locale);
				$id = 'page_detail.'.$pageSub.'.text_list.text_'.($i+1);
				if ($catalogue->defines($id))
				{
					$pageDesc['text_list'][] = array('text' => $translator->trans('page_detail.'.$pageSub.'.text_list.text_'.($i+1)));
				}
				else
				{
					break;
				}
			}
			if($pageSub == 'contacts')
			{
				$pageDesc['form'] = array(
										'name' => $translator->trans('page_detail.'.$pageSub.'.form.name'),
										'email' => $translator->trans('page_detail.'.$pageSub.'.form.email'),
										'telephone' => $translator->trans('page_detail.contacts.form.telephone'),
										'message' => $translator->trans('page_detail.'.$pageSub.'.form.message'),
										'send' => $translator->trans('page_detail.'.$pageSub.'.form.send')
									);
			}
		}
		else
		{
			$pageDesc = array(
				'title' => $translator->trans('page_detail.boats.'.$pageSub.'.title'),
				'sub_title' => $translator->trans('page_detail.boats.'.$pageSub.'.sub_title'),
				'text_list' => array()
			);
			for($i = 0; $i < 15; $i++)
			{
				$locale = $translator->getLocale();
				$catalogue = $translator->getCatalogue($locale);
				$id = 'page_detail.boats.'.$pageSub.'.text_list.text_'.($i+1);
				if ($catalogue->defines($id))
				{
					$pageDesc['text_list'][] = array('text' => $translator->trans('page_detail.boats.'.$pageSub.'.text_list.text_'.($i+1)));
				}
				else
				{
					break;
				}
			}
		}
		
		return $this->render('home/page_detail.html.twig', array('page'=> $page, 'pageSub'=> $pageSub, 'pageInfo' => $pageInfo, 'pageDesc' => $pageDesc));
	}
	
	public function productDetail(Request $request, $page, $pageSub, TranslatorInterface $translator)
    {
		$request = $request->getlocale();
		
		$pageInfo = array();
		$pageInfo = array('title'=> $translator->trans('home.title'), 'sub_title' => $translator->trans('home.sub_title'), 'text' => $translator->trans('home.text'));
		
		$pageDesc = array(
				'text_list' => array(),
				'add_info' => array(
					'title' => $translator->trans('product_detail.'.$pageSub.'.add_info.title'),
					'text_list' => array()
				)
			);
		
		for($i = 0; $i < 15; $i++)
		{
			$locale = $translator->getLocale();
			$catalogue = $translator->getCatalogue($locale);
			$id1 = 'product_detail.'.$pageSub.'.text_list.text_'.($i+1);
			$id2 = 'product_detail.'.$pageSub.'.add_info.text_list.text_'.($i+1);
			if ($catalogue->defines($id1))
				array_push($pageDesc['text_list'],['text' => $translator->trans('product_detail.'.$pageSub.'.text_list.text_'.($i+1))]);
			if ($catalogue->defines($id2))
				array_push($pageDesc['add_info']['text_list'],['text' => $translator->trans('product_detail.'.$pageSub.'.add_info.text_list.text_'.($i+1))]);
		}
		$arrayAux = array();
		$arrayAux = array(
			'title' => $translator->trans('product_detail.'.$pageSub.'.title'),
			'price' => $translator->trans('product_detail.'.$pageSub.'.price'),
			'book' => $translator->trans('product_detail.'.$pageSub.'.book'),
			'pdf_info' => array(
				'title' => $translator->trans('product_detail.'.$pageSub.'.pdf_info.title'),
				'text_1' => $translator->trans('product_detail.'.$pageSub.'.pdf_info.text_1'),
				'link' => $translator->trans('product_detail.'.$pageSub.'.pdf_info.link')
			),
			'cancel_pol' => array(
				'title' => $translator->trans('product_detail.'.$pageSub.'.cancel_pol.title'),
				'text_list' => array(
					'text_1' => $translator->trans('product_detail.'.$pageSub.'.cancel_pol.text_list.text_1'),
					'text_2' => $translator->trans('product_detail.'.$pageSub.'.cancel_pol.text_list.text_2'),
					'text_3' => $translator->trans('product_detail.'.$pageSub.'.cancel_pol.text_list.text_3')
				)
			)
		);
		$pageDesc =array_merge($pageDesc,$arrayAux);
		
		return $this->render('home/product_detail.html.twig', array('page'=> $page, 'pageSub'=> $pageSub, 'pageInfo' => $pageInfo, 'pageDesc' => $pageDesc));
	}
	
	public function privateDetail(Request $request, $page, $pageSub, TranslatorInterface $translator)
    {
		$request = $request->getlocale();
		
		$pageInfo = array();
		$pageInfo = array('title'=> $translator->trans('home.title'), 'sub_title' => $translator->trans('home.sub_title'), 'text' => $translator->trans('home.text'));
		
		if($pageSub == 'gift_voucher')
		{
			$pageDesc = array(
				'title' => $translator->trans('private_detail.'.$pageSub.'.title'),
				'text_list' => array(
					'text_1' => $translator->trans('private_detail.'.$pageSub.'.text_list.text_1'),
					'text_2' => $translator->trans('private_detail.'.$pageSub.'.text_list.text_2'),
					'text_3' => $translator->trans('private_detail.'.$pageSub.'.text_list.text_3'),
				),
				'payer' => array(
					'title' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.title'),
					'name' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.name'),
					'surname' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.surname'),
					'email' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.email'),
					'telephone' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.telephone'),
					'persons' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.persons'),
					'obs' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.obs'),
					'obs_label' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.obs_label'),
					'experiences_title' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences_title'),
					'experiences_label' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences_label'),
					'experiences' => array(
						'1' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.1'),
						'2' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.2'),
						'3' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.3'),
						'4' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.4'),
						'5' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.5'),
						'6' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.6'),
						'7' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.7'),
						'8' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.8'),
						'9' => $translator->trans('private_detail.'.$pageSub.'.text_list.payer.experiences.9'),
					),
				),
				'destiny' => array(
					'title' => $translator->trans('private_detail.'.$pageSub.'.text_list.destiny.title'),
					'name' => $translator->trans('private_detail.'.$pageSub.'.text_list.destiny.name'),
					'surname' => $translator->trans('private_detail.'.$pageSub.'.text_list.destiny.surname'),
					'email' => $translator->trans('private_detail.'.$pageSub.'.text_list.destiny.email'),
					'telephone' => $translator->trans('private_detail.'.$pageSub.'.text_list.destiny.telephone')
				),
			);
			
		}
		else
		{
			$pageDesc = array(
				'title' => $translator->trans('private_detail.'.$pageSub.'.title'),
				'text_list' => array()
			);
			
			for($i = 0; $i < 15; $i++)
			{
				$locale = $translator->getLocale();
				$catalogue = $translator->getCatalogue($locale);
				$id1 = 'private_detail.'.$pageSub.'.text_list.text_'.($i+1);
				if ($catalogue->defines($id1))
					array_push($pageDesc['text_list'],['text' => $translator->trans('private_detail.'.$pageSub.'.text_list.text_'.($i+1))]);
			}
			
			if($pageSub == 'private_photography_tour')
			{
				$arrayAux = array();
				$arrayAux = array(
					'commemtary' => array(
						'text' => $translator->trans('private_detail.'.$pageSub.'.commemtary.text'),
						'author' => $translator->trans('private_detail.'.$pageSub.'.commemtary.author')
					)
				);
				
				$pageDesc = array_merge($pageDesc,$arrayAux);
			}
		}
		
		return $this->render('home/private_detail.html.twig', array('page'=> $page, 'pageSub'=> $pageSub, 'pageInfo' => $pageInfo, 'pageDesc' => $pageDesc));
	}
	
	public function userTranslation($lang,$page)
    {    
        $this->session->set('_locale', $lang);
		
		return $this->redirectToRoute($page);
    }
	
	public function sendEmail(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
	{
			
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);

        $err = array();
		$locale = $request->getlocale();
		$host = $request->getHost();
        
        //IF FIELDS IS NULL PUT IN ARRAY AND SEND BACK TO USER
        $request->request->get('contact_name') ? $name = $request->request->get('contact_name') : $err[] = 'contact_name';
        $request->request->get('contact_email') ? $email = $request->request->get('contact_email') : $err[] = 'contact_email';
        $request->request->get('contact_telephone') ? $telephone = $request->request->get('contact_telephone') : $err[] = 'contact_telephone';
        $request->request->get('contact_message') ? $information = $request->request->get('contact_message') : $err[] = 'contact_message';
        
        if($err){
            $response = array(
                'status' => 0,
                'message' => 'fields empty',
                'data' => $err,
                'mail' => null,
                'locale' => $locale
            );
            return new JsonResponse($response);
        }
        //NO FAKE DATA
        $this->noFakeName($name) == 1 ? $err[] = 'contact_name' : false;
		$this->noFakeEmails($email) == 1 ? $err[] = 'contact_email' : false;
        $this->noFakeTelephone($telephone) == 1 ? $err[] = 'contact_telephone' : false;
        if($err){
            $response = array(
                'status' => 2,
                'message' => 'invalid fields',
                'data' => $err,
                'mail' => null,
                'locale' => $locale
            );
            return new JsonResponse($response);
        }
        else
		{
			$transport = (new \Swift_SmtpTransport($company->getEmailSmtp(), $company->getEmailPort(), $company->getEmailCertificade()))
            ->setUsername($company->getEmail())
            ->setPassword($company->getEmailPass());
		
			$mailer = new \Swift_Mailer($transport);
						
			$subject = $translator->trans('page_detail.contacts.form.subject');
						
			$message = (new \Swift_Message($subject))
            ->setFrom([$company->getEmail() => $company->getName()])
            ->setTo([$request->request->get('contact_email') => $request->request->get('contact_name'), $company->getEmail() => $company->getName() ])
            ->addPart($subject, 'text/plain')
            ->setBody(
                $this->renderView(
                    'emails/emailContact-'.$locale.'.html.twig',
                    array(
                        'name' => $name,
                        'email' => $email,
                        'telephone' => $telephone,
                        'message' => $information,
                        'logo' => $this->getHost($request).'/upload/gallery/'.$company->getLogo(),
                    )
                ),
                'text/html'
            );
            $send = $mailer->send($message);
        }
        
        
        $response = array(
            'status' => 1,
            'message' => 'all valid',
            'data' =>  'success',
            'mail' => $send,
            'locale' => $locale
		);
        
        return new JsonResponse($response);
        
    }

    private function noFakeEmails($email)
	{
        $invalid = 0;        
        if($email){
            $validator = new \EmailValidator\Validator();
            $validator->isEmail($email) ? false : $invalid = 1;
            $validator->isSendable($email) ? false : $invalid = 1;
            $validator->hasMx($email) ? false : $invalid = 1;
            $validator->hasMx($email) != null ? false : $invalid = 1;
            $validator->isValid($email) ? false : $invalid = 1;
        }
        return $invalid;
    }
	
    private function noFakeName($a)
	{
        $invalid = 0;        
        if($a)
            $invalid = preg_replace("/[^!@#\$%\^&\*\(\)\[\]:;]/", "", $a);
        return $invalid;
    }

    private function noFakeTelephone($a)
	{
        $invalid = 0;        
        if($a)
            $invalid = preg_replace("/[0-9|\+?]{0,2}[0-9]{5,12}/", "", $a);
        return $invalid;
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

	private function defaultUserLocale(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$defaultUserLocale = $request->getLocale() == "en" || $request->getLocale() == "en_EN" ? 'en_EN':'pt_PT';
		$userLocale = $em->getRepository(Locales::class)->findOneBy(['name'=> $defaultUserLocale]);
		return $userLocale;
	}
}