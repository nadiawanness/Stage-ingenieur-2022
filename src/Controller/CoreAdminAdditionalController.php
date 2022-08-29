<?php

namespace App\Controller;

use App\Service\CoreAdminAdditionalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\AccessToken;
use App\Entity\CoreUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoreAdminAdditionalController extends AbstractController
{
   /*  #[Route('/core/admin/additional', name: 'app_core_admin_additional')]
    public function index(): Response
    {
        return $this->render('core_admin_additional/index.html.twig', [
            'controller_name' => 'CoreAdminAdditionalController',
        ]);
    } */

    #[Route('/api/getAdmin',name: 'app_get_admin',methods: ['GET'])]
    public function getAdmins(CoreAdminAdditionalService $admin,Request $request): Response{

                
       
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) // verifier si l'utilisateur connecte est
                                                                                                           // un super admin pour faire cette action  
                    {
                        return new Response (
                            $admin->getAdmin($request) 
                           
                        );
                    }
                else 
                    return $this->json([
                       "code" => "400",
                       "messsage" => "Role Super Admin is required ! "
                   ]); 
               
         
    } 


    #[Route('/api/postAdmin/{idOrganization}/{idCountry}/{idRole}',name: 'app_post_admin',methods: ['POST'])]
    //#[IsGranted('ROLE_SUPER_ADMIN',message: 'Sorry you are not allowed to add an admin ! you need to be a super admin ')]
    public function postAdmin(
        CoreAdminAdditionalService $admin ,
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher ,
        $idOrganization ,
        $idCountry ,
        $idRole
    ){
       
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
                    {
                        return $this->json (
                            $admin->addAdmin($request,$validator,$userPasswordHasher,$idOrganization,$idCountry,$idRole) ,
                            201 ,
                            [] ,
                            [
                                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => 
                                function($organisation){
                                    return 
                                    //$role->addUser($role->getUsers());
                                    //$role->getUsers();
                                    //$user->getEmail();
                                    //$organisation->getCompanyName();
                                    'nadia' ;
                                    //$role->getNom();
                                }
                            ]
                        );
                    }
                else 
                    return $this->json([
                       "code" => "400",
                       "messsage" => "Role admin is required ! "
                   ]); 


    }

    
    #[Route('/api/getTokenDetails',name: 'app_get_token_details',methods: ['POST'])]
    public function getTokenDetails(
        EntityManagerInterface $em ,
        Request $request
    ){
        $em->getConnection()->beginTransaction();
        try{

        $token = $this->container->get('security.token_storage')->getToken();
        dd($this->container->get('lexik_jwt_authentication.encoder')->encode(['username' => $user->getUsername()]));
        $user = $token->getUser();
        $access = new AccessToken(); 
        $access->setSingleUseToken($token);
        $access->setPunchout(false);
        $access->setAttributes([
            'id_user' => $user->getId() ,
            'user_name' => $user->getUsername() ,
            'email_user' => $user->getEmail() ,
            'type_user' => $user->getType() ,
            'status_user' => $user->isEnabled() ,
            'delegate_user' => $user->isHasDelegate() ,
            //'token_expiration_date' => $token->getTokenExpirationDate() ,
            'role_user' => $user->getRoles() 
        ]);
        $em->persist($access);
        $em->flush();
        $em->getConnection()->commit();
        //dd($access);
        return new JsonResponse($access);

        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }
    }
     //fonction de test 
    /* #[Route('/api/verifyRole',name: 'app_verify_role',methods: ['GET'])]
    public function verifyRole()
    {
        if($this->getUser())
        {
            $auth_checker = $this->container->get('security.authorization_checker');
            /* if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
               
                /* $token = $this->container->get('security.token_storage')->getToken();
                $user = $token->getUser(); // recuperer un utilisateur d'apres le token passe 
                return $this->json($user); 
                return $this->json('true role');
            }
            else 
                return $this->json('false role'); 
                $isRoleAdmin = $auth_checker->isGranted('ROLE_USER');
                return $this->json($isRoleAdmin);
            
                
        }
        else 
            return $this->json('not connected ! ');
        
    } */ 
}
