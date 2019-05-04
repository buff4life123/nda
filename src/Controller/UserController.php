<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\SuperUser;
use App\Entity\Manager;
use App\Form\ChangePasswordType;

class UserController extends AbstractController
{
    public function listUser(Request $request, ValidatorInterface $validator)
    {
        $loggedUser = $this->getUser();
        $users      = array();
        $role     = $request-> request ->get('role');

        $em         = $this->getDoctrine()->getManager();
        $admins     = $em->getRepository(Admin::class)->findAll();
        $managers   = $em->getRepository(Manager::class)->findAll();

        if ($loggedUser instanceof SuperUser) {
            $superusers = $em->getRepository(SuperUser::class)->findAll();
            $users      = array_merge($this -> dataUser($admins), $this -> dataUser($superusers), $this -> dataUser($managers));
        } else {
            $users      = array_merge($this -> dataUser($admins), $this -> dataUser($managers));
        }
           
        return $this->render('admin/app-users.html', array(
            'users' =>  $users,
            )
        );
    }

    public function statusUser (Request $request)
    {
        $response = array();

        $id     = $request-> request ->get('id');
        $status = $request-> request ->get('status');
        $role = $request-> request ->get('role');

        $em     = $this->getDoctrine()->getManager();
        $role == "ROLE_ADMIN"?$user = $em->getRepository(Admin::class)->find($id): $user = $em->getRepository(Manager::class)->find($id);
        
        if (!$user) {
            $reponse = array('message' => 'fail', 'data' =>'Utilizador Não encontrado ', 'request'=>'');
        }    
        else {
            $user->setStatus($status);
            $em->flush();
            $response = array('message' => 'success', 'data' => $user->getStatus(), 'request '=> $status);
        }
        return new JsonResponse($response);
    }

    public function deleteUser(Request $request)
    {
        $response = array();
        
        $id = $request->request->get('id');
        $role = $request-> request ->get('role');
        $em = $this->getDoctrine()->getManager();
        $role == "ROLE_ADMIN"?$user = $em->getRepository(Admin::class)->find($id): $user = $em->getRepository(Manager::class)->find($id);

        if (!$user) {
            $response = array('message'=>'fail', 'data' => 'Utlizador #'.$id.' não existe!', 'request' => $id);
        }
        else{
            $em->remove($user);
            $em->flush();
            $response = array('message'=>'success', 'data' => $user->getId(), 'request' => $id);
        }

        return new JsonResponse($response);

    }


    public function passwordUser(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        $role = $request-> request ->get('role');

        $em = $this->getDoctrine()->getManager();

        if($request->query->get('id')){
            $id = $request->query->get('id');
            //$user = $em->getRepository(Admin::class)->find($id);
            $role == "ROLE_ADMIN"?$user = $em->getRepository(Admin::class)->find($id): $user = $em->getRepository(Manager::class)->find($id);

            $passwordForm = $this->createForm(ChangePasswordType::class, $user);
            
            return $this->render('admin/user-password.html', array(
                'passwordForm' => $passwordForm->createView(),
                //'editFormErrors' => true
            ));
        }
        
        else{

            $id = $request->request->get('id');

            //$user = $em->getRepository(Admin::class)->find($id);
            $role == "ROLE_ADMIN"?$user = $em->getRepository(Admin::class)->find($id): $user = $em->getRepository(Manager::class)->find($id);
        
            $form = $this->createForm(ChangePasswordType::class, $user);
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {

                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

                $user->setPassword($password);
                $em->persist($user);
                $em->flush();

                $response = array('message'=>'success', 'data' => $user->getId(), 'request' => $id);      
            } 
            else {

                $errorMessages = array();

                foreach ($form['plainPassword']->getErrors(true) as $error) {
                    $errorMessages [] = $error->getMessage();
                }           
              
                $response = array('message'=>'fail', 'data' => $errorMessages, 'request' => $id);
            }

            return new JsonResponse($response);
        }

    }

    public function dataUser($userType) {
        $u = array();
        foreach($userType as $user) {
            $u[] = array(
                'id'      =>  $user->getId(),
                'email'   =>  $user->getEmail(),
                'username'    =>  $user->getUsername(),
                'status'  =>  $user->getStatus(),
                'role'    =>  $user->getRoles(),
            
            );
        }
        return $u;
    }

    public function roleUser() {

    }

}

?>