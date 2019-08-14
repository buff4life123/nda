<?php
namespace App\Controller;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Amount;
use App\Entity\PriceTranslation;
use App\Entity\ProductDescriptionTranslation;
use App\Entity\Booking;
use App\Entity\Event;
use App\Entity\Locales;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\File\File;
use App\Form\ProductType;
use App\Service\FileUploader;
use App\Service\ImageResizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\DBAL\DBALException;
use App\Service\MoneyFormatter;


class ProductController extends AbstractController
{

    private $products_images_directory;
    
    public function __construct($products_images_directory)
    {
        $this->products_images_directory = $products_images_directory;
    }

    public function productNew(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $l = $request->getLocale() ? $request->getLocale() : 'pt';

        $locale = $em->getRepository(Locales::class)->findOneBy(['name' => $l]);

        if(!$locale)
            $locale = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt']);

        $locales = $em->getRepository(Locales::class)->findAll();

        $product = new Product();

        $categories = $em->getRepository(Category::class)->findAll();

        $c = array();

        foreach ($categories as $category)
            $c[] = array('id' => $category->getId(), 'name' => $category->getCurrentTranslation($locale));

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        return $this->render('admin/product-new.html',
            array(
                'form' => $form->createView(),
                'categories' => $c,
                'locales' => $locales
                ));
    }

    public function productAdd(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer, TranslatorInterface $translator)
    {
        $product = new Product();
               
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

            if($form->isSubmitted()){
                
                if($form->isValid()){
                    $product = $form->getData();

                    $totals = $em->getRepository(Product::class)->findAll();
                    $category = $em->getRepository(Category::class)->find($request->request->get('category'));

                    $file = $product->getImage();

                    if ($file) {
                        $fileName = $fileUploader->upload($file);               
                        $imageResizer->resize($fileName);
                        $product->setImage($fileName);
                    }
                    else{
                        $product->setImage($this->products_images_directory.'/no-image.png');
                    }

                try {
                    
                    $product->setOrderBy(count($totals)+1);
                    $product->setCategory($category);

                    $em->persist($product);

                    $em->flush();

                    $response = array(
                        'status' => 1,
                        'message' => 'success',
                        'data' => $product->getId());
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

    public function productList(Request $request, MoneyFormatter $moneyFormatter)
    {
        $em = $this->getDoctrine()->getManager();

        $l = $request->getLocale() ? $request->getLocale() : 'pt';

        $locale = $em->getRepository(Locales::class)->findOneBy(['name' => $l]);

        if(!$locale)
            $locale = $em->getRepository(Locales::class)->findOneBy(['name' => 'pt']);

        $products = $em->getRepository(Product::class)->findBy([],['orderBy' => 'ASC']);

        $cA = array();

        foreach ($products as $c){
            
            foreach ($c->getEvent() as $evt)
                $ev = $evt->getEvent();

            $cA[]=array(
                'order' => $c->getOrderBy(),
                'id' => $c->getId(),
                'is_active' => $c->getIsActive(),
                'category' => $c->getCategory()->getCurrentTranslation($locale),
                'highlight' => $c->getHighlight(),
                'title' => $c->getCurrentTranslationName($locale),
                'availability' => $c->getAvailability(),
                'duration' => $c->getDuration(),
                'warranty_payment' => $c->getWarrantyPayment(),
             );

        }    
        return $this->render('admin/product-list.html', array(
            'products' =>  $cA));
    }



    public function productShowEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer)
    {

        $productId = $request->request->get('id');
        
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->find($productId);

        if ($product->getImage()) {

            $path = file_exists($this->products_images_directory.'/'.$product->getImage()) ?
                $this->products_images_directory.'/'.$product->getImage()
                :
                $this->products_images_directory.'/no-image.png';

            $product->setImage(new File($path));
        }

        else
            $product->setImage(new File($this->products_images_directory.'/no-image.png'));

        $form = $this->createForm(ProductType::class, $product);

        return $this->render('admin/product-edit.html',array(
            'form' => $form->createView(),
            'product' => $product
        ));
    }


    public function productEdit(Request $request, ValidatorInterface $validator, FileUploader $fileUploader, ImageResizer $imageResizer, TranslatorInterface $translator)
    {
        $productId = $request->request->get('id');
        
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->find($productId);

        $img = $product->getImage();

        if ($product->getImage()) {
            
            $path = file_exists($this->products_images_directory.'/'.$product->getImage()) ?

            $this->products_images_directory.'/'.$product->getImage()
            :
            $this->products_images_directory.'/no-image.png';

            $product->setImage(new File($path));
        }

        else

            $product->setImage(new File($this->products_images_directory.'/no-image.png'));

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
            
        if($form->isSubmitted()){
                
            if($form->isValid()){ 
                
                $deleted = 1;
                $product = $form->getData();
                $file = $product->getImage();

                if (is_object($file)) {
                    $fileName = $fileUploader->upload($file);               
                    $imageResizer->resize($fileName);
                    $product->setImage($fileName);
                    
                    //remove from folder older image

                    $fileSystem = new Filesystem();

                    if ($img && $img != 'no-image.png') {
                        try {
                            $fileSystem->remove($this->products_images_directory.'/'.$img);
                        } 
                        catch (IOExceptionInterface $exception) {
                            $deleted = '0 '.$exception->getPath();
                        }
                    }
                }
                else
                    $product->setImage($img);

                try {
                    $em->persist($product);
                    $em->flush();

                    $response = array(
                        'result' => 1,
                        'message' => 'success',
                        'image' => $deleted,
                        'data' => $product->getId());
                } 
                catch(DBALException $e){

                    if (preg_match("/'event'/i", $e))
                        $a = array( "Insira pelo menos 1 hora.");

                    else if (preg_match("/'children_price'/i", $e))
                        $a = array("Preço Criança (€)* não pode ser vazio, insira 0 ou maior.");

                    else
                        $a = array("Contate administrador sistema sobre: ".$e->getMessage());

                    $response = array(
                        'result' => 0,
                        'message' => 'fail',
                        'data' => $a);
                }
            }
            
            else{   
                $response = array(
                    'result' => 0,
                    'message' => 'fail',
                    'data' => $this->getErrorMessages($form)
                );
            }
        }
        return new JsonResponse($response);
    }

    public function productDelete(Request $request, TranslatorInterface $translator)
    {
        $deleted = 1;
        $response = array();
        $productId = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $product = $em->getRepository(Product::class)->find($productId);
        
        if (!$product) {
            return new JsonResponse(array('status'=> 0, 'message' => 'Categoria #'.$productId . ' não existe.'));
        }
        
        //search bookings if already bought this product, DO NOT DELETE send info to user
        $booking = $em->getRepository(Booking::class)->findDeleteProduct($product);
        
        if (count($booking) > 0)
            return new JsonResponse(array('status'=> 0, 'message' => $product->getNamePt() . ' não pode ser apagada. Já existem Reservas associadas'));

        else{

            $blocked = $em->getRepository(BlockDates::class)->findOneBy(['product' => $product]);
            $event = $em->getRepository(Event::class)->findOneBy(['product' => $product]);
            $available = $em->getRepository(Available::class)->findAll(['product' => $product]);
            
            if($available)

                foreach ( $available as $availables) {
                    $em->remove($availables);
                    $em->flush();
                }

            $img = $product->getImage();
            $em->remove($blocked);
            $em->remove($event);
            $em->remove($product);
            $em->flush();

            //remove from folder image

            $fileSystem = new Filesystem();

            if ($img && $img != 'no-image.png') {
                try {
                    $fileSystem->remove($this->products_images_directory.'/'.$img);
                } 
                catch (IOExceptionInterface $exception) {
                    $deleted = '0 '.$exception->getPath();
                }
            }

            return new JsonResponse(array('status' => 1, 'message' => 'Categoria foi apagada'));
        }
        return new JsonResponse($response);
    }


    public function productOrder(Request $request, TranslatorInterface $translator)
    {
        $result = $request->request->get('result');

        if (!$result)
        
           return new JsonResponse(array('status'=> 0, 'message' => 'nada para ordenar', 'data' => null));

        $order = json_decode($result);
    
        $em = $this->getDoctrine()->getManager();  

        foreach ($order as $orderBy) {
            $product = $em->getRepository(Product::class)->find($orderBy->id);
            $product->setOrderBy($orderBy->to);
            $em->persist($product);
            $em->flush();
        }

        $response = array('status'=> 1, 'message' => 'success', 'data' => count($order));
        
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