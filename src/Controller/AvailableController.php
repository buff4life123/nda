<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\Booking;
use App\Entity\Event;
use App\Entity\Logs;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Available;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\DBALException;

class AvailableController extends AbstractController
{

    public function adminAvailable(Request $request)
    {
        return $this->render('admin/available.html');
    }

    //TEMPLATE LOAD
    public function adminAvailableNew(Request $request)
    {
        $allEvents = array();

        $productId = $request->request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        
        $product = $em->getRepository(Product::class)->find($productId);

        $event = explode(',',$product->getEvent()[0]->getEvent());
        
        foreach ($event as $ev)
            array_push($allEvents, $ev);

        return $this->render('admin/available-new.html',array(
            'product' => $product,
            'event' => $allEvents
        ));
    }

    public function adminAvailableCreate(Request $request, TranslatorInterface $translator){

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->request->get('product'));
        
        if(!$product) {

            $response = array(
                'status' => 0,
                'message' => 'category_not_found',
                'data' => null);

            return new JsonResponse($response);
        }

        $eventStart = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('startDate').' '.$request->request->get('event'));
        
        $s = explode(":",$product->getDuration());

        $seconds = (int)$s[0]*3600 + (int)$s[1]*60;
        //duration of event
        $interval = \DateTime::createFromFormat('U', ($eventStart->format('U') + $seconds));
        
        if($request->request->get('endDate'))
            $eventEnd = \DateTime::createFromFormat('d/m/Y', $request->request->get('endDate'));
        
        $isRecurrent = $request->request->get('startDate') != $request->request->get('endDate') && $request->request->get('endDate') ? 
        true : false;

        $eventsCreated = 0;

        // User wants to create a recurrent event.
        if ($isRecurrent) {

            $rule = (new \Recurr\Rule)
                ->setStartDate($eventStart)
                ->setTimezone('Europe/Lisbon')
                ->setFreq('DAILY')
                ->setUntil($eventEnd);

            $transformer = new \Recurr\Transformer\ArrayTransformer();

            $recurrencyRef = 0;

            foreach ($transformer->transform($rule) as $day) {

             //We do not allow to create an event for this product with the same date and start time.

                $starts = \DateTime::createFromFormat('d/m/Y H:i', $day->getStart()->format('d/m/Y').' '.$request->request->get('event'));

                $eventWithSameDateAndHour = $em->getRepository(Available::class)->count(array(
                'product' => $product,
                'datetimestart' => $starts
                ));
                
                if ($eventWithSameDateAndHour) {
                    $response = array(
                        'status' => 0,
                        'message' => 'No periodo temporal inserido, já existe pelo menos 1 disponbilidade criada, escolha outra hora!',
                        'data' => null);
                        // We must to delete the first event because it was already saved in DB.
                    if ($recurrencyRef > 0) {
                    // First detach all managed entities; otherwise they will be saved.
                    $em->clear();
                    // Merge the firstEvent so we can remove it from DB.
                    $firstEvent = $em->merge($firstEvent);
                    $em->remove($firstEvent);
                    $em->flush();
                }

                $response = array(
                    'status' => 0,
                    'message' => 'No periodo temporal inserido, já existe pelo menos 1 disponibilidade criada, escolha outra hora!',
                    'data' => null);
                return new JsonResponse($response);
            
            }

            $eventsCreated++;

            $dayEventEnd = \DateTime::createFromFormat('d/m/Y H:i', $day->getStart()->format('d/m/Y').' '.$interval->format('H:i'));
            $available = new Available();
            $available->setDatetimeStart($day->getStart());
            $available->setProduct($product);
            $available->setLotation($product->getAvailability());
            $available->setStock($product->getAvailability());
            $available->setDatetimeEnd($dayEventEnd);
            $em->persist($available);
        }

        $em->flush();

        }
        else{

            $eventsCreated++;

            $dayEventEnd = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('startDate').' '.$interval->format('H:i'));
            $available = new Available();
            $available->setDatetimeStart($eventStart);
            $available->setProduct($product);
            $available->setLotation($product->getAvailability());
            $available->setStock($product->getAvailability());
            $available->setDatetimeEnd($dayEventEnd);
            $em->persist($available);
            $em->flush();
        }

        $response = array(
            'status' => 1,
            'message' => 'Foram criadas '.$eventsCreated.' disponibilidades, na Categoria '.$product->getNamePt(),
            'recurrent' => $isRecurrent,
            'data' => null,
            );
        return new JsonResponse($response);
   }


    /**
     * Gets events for the calendar (only accessible by ajax).
     * @param int $productId
     * @param Request $request     
     */
    public function adminAvailableList(Request $request, TranslatorInterface $translator)
    {       
        $em = $this->getDoctrine()->getManager();
        
        $start = \DateTime::createFromFormat('U', $request->query->get('start'));

        $end = \DateTime::createFromFormat('U', $request->query->get('end'));

        $products = $em->getRepository(Product::class)->findBy([],['orderBy' => 'ASC']);

        $availables = $em->getRepository(Available::class)->findAvailableFromInterval($start, $end);

        $data_events = array();
        
        $data_resources = array();

        $rand_color = array('','blue','green','black','blueviolet','brown','cadetblue','cornflowerblue','darkcyan','orange');

        $id = null;

        $counter = 0;

        foreach ($products as $product) {
            $counter++;
            $data_resources[] = array(
                'eventColor' => $rand_color[$counter],
                'id' => $product->getId(),
                'title' => $product->getNamePt(),
                'order' => $product->getOrderBy()
            );
        }

        foreach ($availables as $available) {

            $finalEnd = str_replace(' ','T',$available->getDatetimeEnd()->format('Y-m-d H:i:s'));
            $finalStart = str_replace(' ','T',$available->getDatetimeStart()->format('Y-m-d H:i:s'));

            $data_events[] = array(
                'id' => $available->getId(),
                'resourceId' => $available->getProduct()->getId(),          
                'start' => $finalStart,
                'end' => $finalEnd,
                'title' =>'Total: '.$available->getLotation().' Disponivel: '.$available->getStock(),
                'textColor' => $available->getStock().'**'.$available->getLotation().'**'.$available->getProduct()->getNamePt().'<br>Data: '.$available->getDatetimeStart()->format('d/m/Y H:i'),
            );
        }
        
        return $this->json(array('events' => $data_events, 'resources' => $data_resources));        
    }

    public function adminAvailableEdit(Request $request, TranslatorInterface $translator){

        $em = $this->getDoctrine()->getManager();
        
        $available = $em->getRepository(Available::class)->find($request->request->get('id'));

        if(!$available)
            $response = array(
                'status' => 0,
                'message' => 'Disponibilidade não encontrada!',
                'data' => null);

        $available->setLotation($request->request->get('lotation'));
        $available->setStock($request->request->get('stock'));

        try {
            $em->persist($available);
            $em->flush();  

            $response = array(
                'status' => 1,
                'message' => 'success',
                'data' => ['id' => $available->getId(), 'stock' => $available->getStock(), 'lotation' => $available->getLotation() ]
            );

        } catch (Exception $e) {
       
            $response = array(
                'status' => 0,
                'message' => 'Exception error',
                'data' => $e->getMessage().' '.$available->getId()
                );
        }

        return new JsonResponse($response);
   }

    public function adminAvailableDelete(Request $request, TranslatorInterface $translator){

        $em = $this->getDoctrine()->getManager();
    
        $available = $em->getRepository(Available::class)->find($request->request->get('id'));
        
        if(!$available)
            return new JsonResponse(array(
                'status' => 0,
                'message' => 'Disponibilidade não foi encontrada!',
                'data' => null));

        $booking = $em->getRepository(Booking::class)->findOneBy(['available' => $available]);
            
        if($booking)
            return new JsonResponse(array(
                'status' => 0,
                'message' => 'Não é possivel apagar a Disponibilidade, tem reservas associadas!',
                'data' => null));
        
        $logTxt = 'Utilizador: '.$this->getUser()->getUsername().'Evento: #'.$available->getId().'
        Start: '.$available->getDatetimeStart()->format('d/m/Y H:i:s').' End: '.$available->getDatetimeEnd()->format('d/m/Y H:i:s').'
        Lotação : '.$available->getLotation().' Stock : '.$available->getStock().'Categoria: '.$available->getProduct()->getNamePt();

        $now = new \DateTime('now');
        $log = new Logs();
        $log->setDatetime($now);
        $log->setLog($logTxt);
        $log->setStatus('delete');
        $em->persist($log);
        $em->flush();

        $em->remove($available);
        $em->flush();

        return new JsonResponse(array(
            'status' => 1,
            'message' => 'Disponibilidade foi Apagada',
            'data' => $request->request->get('id')));
    }

}