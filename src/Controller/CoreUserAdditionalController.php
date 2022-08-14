<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CoreUserAdditionalService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\CoreUserRepository;
class CoreUserAdditionalController extends AbstractController
{
    #[Route('/core/user/additional', name: 'app_core_user_additional')]
    public function index(): Response
    {
        return $this->render('core_user_additional/index.html.twig', [
            'controller_name' => 'CoreUserAdditionalController',
        ]);
    }

    #[Route('/api/getSimpleUser',name: 'app_get_simple_user',methods: ['GET'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to see the  simple users list ! You need to get the admin role .')]
    public function getSimpleUsers(
        CoreUserAdditionalService $simpleUser ,
        Request $request
        ): Response
        {

           
                    if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                        {
                            return new Response (
                                $simpleUser->getSimpleUser($request)
                            );
                        }
                    else 
                        return $this->json([
                            'error' => 'error',
                            'messsage' => 'Role admin is required ! '
                        ]);
                    
                
         
        } 

    #[Route('/api/postSimpleUser/{idOrganization}',name: 'app_post_simple_user',methods: ['POST'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to add a simple user ! You need to get the admin role .')]
    public function postSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher , 
        $idOrganization
    ){
        
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        return $this->json (
                            $simpleUser->addSimpleUser($request,$validator,$userPasswordHasher,$idOrganization)
                        );
                    }
                else 
                    return $this->json('Role Admin is required to do this action');
          
        
    }

    #[Route('/api/changeStatusUser/{idUser}/{value}',name: 'app_change_status_simple_user', methods: ['PATCH'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to enable a simple user account ! You need to get the admin role .')]
    public function changeStatusSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        $idUser ,
        bool $value
    )
    {
      
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                                return $this->json(
                                 $simpleUser->changeStatusUser($idUser,$value)
                                 );
                    }
                else 
                    return $this->json('Role Admin is required to do this action');
    
       
        
    }


    #[Route('/api/enableSimpleUser/{idUser}/{value}',name: 'app_enable_simple_user', methods: ['PATCH'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to enable a simple user account ! You need to get the admin role .')]
    public function enableSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        $idUser ,
        $value
    )
    {
    
       
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        return $this->json(
                            $simpleUser->enableUser($idUser,$value)
                        );

                    }
                else 
                    return $this->json('Role Admin is required to do this action');
       
    }

    #[Route('/api/disableSimpleUser/{idUser}/{value}',name: 'app_disable_simple_user', methods: ['PATCH'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to enable a simple user account ! You need to get the admin role .')]
    public function disableSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        $idUser ,
        $value
    )
    {
        
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        return $this->json(
                            $simpleUser->disableUser($idUser,$value)
                        );
                    }
                else 
                    return $this->json([
                        'status'=> 400,
                        'messsage' => 'Role admin is required ! '
                    ]);
       
    }

    #[Route('/api/myDetails',name: 'app_my_details',methods: ['GET'])]
    public function myDetails()
    {
        
                $token = $this->container->get('security.token_storage')->getToken();
                $user = $token->getUser();
                return $this->json($user);
      
    }

    #[Route('/api/getSimpleUserByOrganization/{organisation}',name: 'app_get_user_by_organization',methods: ['GET'])]
    public function getUserByOrganization(
        CoreUserRepository $userRepo ,
        $organisation
    )
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            {
                $user = $userRepo->findByOrganization($organisation);
                $p = $this->serializer->serialize($user, 'json');
                return $this->json($p);
            }
        else 
            return 'Role Admin is required to to this action ! ' ;
    }

    #[Route('/api/getUserById/{idUser}',name: 'app_user_by_id',methods: ['GET'])]
    public function getUserById(

        CoreUserAdditionalService $simpleUser ,
        $idUser
    )
    {
       
                if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                    {
                        return new Response($simpleUser->getSimpleUserById($idUser));
                    }
                else
                    return $this->json('Role Admin is required to do this action ! ');
      
    }


}
