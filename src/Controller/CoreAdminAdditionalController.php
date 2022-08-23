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
    //#[IsGranted('ROLE_SUPER_ADMIN',message: 'Sorry you are not allowed to get the admins list ! you need to be a super admin ')]
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


    #[Route('/api/postAdmin',name: 'app_post_admin',methods: ['POST'])]
    //#[IsGranted('ROLE_SUPER_ADMIN',message: 'Sorry you are not allowed to add an admin ! you need to be a super admin ')]
    public function postAdmin(
        CoreAdminAdditionalService $admin,
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasher
    ){
       
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
                    {
                        return $this->json (
                            $admin->addAdmin($request,$validator,$userPasswordHasher)
                        );
                    }
                else 
                    return $this->json([
                       "code" => "400",
                       "messsage" => "Role admin is required ! "
                   ]); 


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
