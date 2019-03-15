<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Locales;

class TranslationController extends AbstractController
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session; 
    }

    public function translate($local = null) {

        $em = $this->getDoctrine()->getManager();
        
        $locales = $em->getRepository(Locales::class)->findOneBy(['name' => $local]);
        
        if(!$locales)
            $locales = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt_PT']);

            $this->session->set('_locale', $locales->getName());

        return $this->redirectToRoute('index');
    }

}

?>