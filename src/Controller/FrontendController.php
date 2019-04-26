<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Company;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use EmailValidator\EmailValidator;
use Symfony\Component\Finder\Finder;

class FrontendController extends AbstractController
{
	private $session;
	
	private $exp_api_key = 'ab09f4752091f568b0f3f30fd8dcf544c242fe20';

	private $url_api_key = 'https://admin.experienceware.pt/';

	public function __construct(SessionInterface $session)
	{
       $this->session = $session;
	}
	
    public function home(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		$ch = curl_init();
		$url = $this->url_api_key.'api/'.$this->exp_api_key.'/products/'.$request->getLocale();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_URL, $url);
    	$p = curl_exec($ch);
    	curl_close($ch);
		$curl_response = json_decode($p);
		
		$iconsFinder = new Finder();
		$iconsFinder->files()->in("../public/images/icons");
    
		return $this->render('index/index.html.twig',  array(
			'page' => 'index', 
			'company' => $company,
			'iconsFinder' => $iconsFinder,
			'products' => $curl_response, 
			'exp_api_key' => $this->exp_api_key, 
			'url_api_key' => $this->url_api_key));
    }

    public function aboutUs(Request $request)
{		
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);
		return $this->render('index/about_us.html.twig',  array(
			'page' => 'about_us',
			'company' => $company,
		));
    }

	function tour($id, $text, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$company = $em->getRepository(Company::class)->find(1);

    	$ch = curl_init();
		$url = $this->url_api_key.'api/'.$this->exp_api_key.'/product/'.$id.'/'.$request->getLocale();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_URL, $url);
    	$p = curl_exec($ch);
    	curl_close($ch);
    	$curl_response = json_decode($p);
		
		

    	return $this->render('index/tour.html.twig', array(
			'page'=> 'tour',
			'company' => $company,
    		'products' => $curl_response,
    		'exp_api_key' => $this->exp_api_key, 
			'url_api_key' => $this->url_api_key,
    		'text' => 'ultimate_tour'));

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
                    
        $err = array();
        $locale = $request->getlocale();
        
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
			$transport = (new \Swift_SmtpTransport($_ENV['EMAIL_SMTP'], $_ENV['EMAIL_PORT'], $_ENV['EMAIL_CERTIFICADE']))
            ->setUsername($_ENV['EMAIL'])
            ->setPassword($_ENV['EMAIL_PASS']);
		
			$mailer = new \Swift_Mailer($transport);
						
			$subject = $translator->trans('page_detail.contacts.form.subject');
						
			$message = (new \Swift_Message($subject))
            ->setFrom([$_ENV['EMAIL'] => $_ENV['EMAIL_USERNAME']])
            ->setTo([$request->request->get('contact_email') => $request->request->get('contact_name'), $_ENV['EMAIL'] => $_ENV['EMAIL_USERNAME'] ])
            ->addPart($subject, 'text/plain')
            ->setBody(
                $this->renderView(
                    'emails/emailContact-'.$locale.'.html.twig',
                    array(
                        'name' => $name,
                        'email' => $email,
                        'telephone' => $telephone,
                        'message' => $information,
                        'logo' => '/assets/images/logo-branco-seasiren-01.svg'
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
	
	public function sendVoucher(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
	{
                    
        $err = array();
        $locale = $request->getlocale();
        
        //IF FIELDS IS NULL PUT IN ARRAY AND SEND BACK TO USER
		//PAYER
        $request->request->get('payer_name') ? $payerName = $request->request->get('payer_name') : $err[] = 'payer_name';
        $request->request->get('payer_surname') ? $payerSurname = $request->request->get('payer_name') : $err[] = 'payer_surname';
        $request->request->get('payer_email') ? $payerEmail = $request->request->get('payer_email') : $err[] = 'payer_email';
        $request->request->get('payer_telephone') ? $payerTelephone = $request->request->get('payer_telephone') : $err[] = 'payer_telephone';
		//DESTINY
		$request->request->get('destiny_name') ? $destinyName = $request->request->get('destiny_name') : $err[] = 'destiny_name';
        $request->request->get('destiny_surname') ? $destinySurname = $request->request->get('destiny_name') : $err[] = 'destiny_surname';
        $request->request->get('destiny_email') ? $destinyEmail = $request->request->get('destiny_email') : $err[] = 'destiny_email';
        $request->request->get('destiny_telephone') ? $destinyTelephone = $request->request->get('destiny_telephone') : $err[] = 'destiny_telephone';
		//EXTRA INFO
        $request->request->get('payer_persons') ? $numberPersons = $request->request->get('payer_persons') : $err[] = 'payer_persons';
        $request->request->get('payer_experiences') && strpos($request->request->get('payer_experiences'), 'Selec') === false ? $experience = $request->request->get('payer_experiences') : $err[] = 'payer_experiences';
        $request->request->get('payer_obs') ? $observations = $request->request->get('payer_obs') : $observations = '';
        
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
        $this->noFakeName($payerName) == 1 ? $err[] = 'payer_name' : false;
        $this->noFakeName($payerSurname) == 1 ? $err[] = 'payer_surname' : false;
        $this->noFakeName($destinyName) == 1 ? $err[] = 'destiny_name' : false;
        $this->noFakeName($destinySurname) == 1 ? $err[] = 'destiny_surname' : false;
		$this->noFakeEmails($payerEmail) == 1 ? $err[] = 'payer_email' : false;
		$this->noFakeEmails($destinyEmail) == 1 ? $err[] = 'destiny_email' : false;
        $this->noFakeTelephone($payerTelephone) == 1 ? $err[] = 'payer_telephone' : false;
        $this->noFakeTelephone($destinyTelephone) == 1 ? $err[] = 'destiny_telephone' : false;
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
			$transport = (new \Swift_SmtpTransport($_ENV['EMAIL_SMTP'], $_ENV['EMAIL_PORT'], $_ENV['EMAIL_CERTIFICADE']))
            ->setUsername($_ENV['EMAIL'])
            ->setPassword($_ENV['EMAIL_PASS']);
		
			$mailer = new \Swift_Mailer($transport);
						
			$subject = $translator->trans('private_detail.gift_voucher.text_list.payer.subject');
						
			$message = (new \Swift_Message($subject))
            ->setFrom([$_ENV['EMAIL'] => $_ENV['EMAIL_USERNAME']])
            ->setTo([$payerEmail => $payerName.' '.$payerSurname, $_ENV['EMAIL'] => $_ENV['EMAIL_USERNAME'] ])
            ->addPart($subject, 'text/plain')
            ->setBody(
                $this->renderView(
                    'emails/emailVoucher-'.$locale.'.html.twig',
                    array(
                        'payer_name' => $payerName,
                        'payer_surname' => $payerSurname,
                        'payer_email' => $payerEmail,
                        'payer_telephone' => $payerTelephone,
                        'destiny_name' => $destinyName,
                        'destiny_surname' => $destinySurname,
                        'destiny_email' => $destinyEmail,
                        'destiny_telephone' => $destinyTelephone,
						'number_persons' => $numberPersons,
						'experience' => $experience,
                        'observations' => $observations,
                        'logo' => "{{ asset('assets/images/logo-branco-seasiren-01.svg') }}"
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
}