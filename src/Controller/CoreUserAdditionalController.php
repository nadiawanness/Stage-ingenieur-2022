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
use App\Entity\CoreUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Repository\CoreOrganizationRepository;
//use Doctrine\Common\Collections\ArrayCollection ;

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
            {       $role = $this->getUser()->getCoreUserRoles();
                    foreach ($role as $role)
                        {
                            $roleAdmin = $role->getCoreRole()->getName();
                        }
    
                    if($roleAdmin == 'ROLE_ADMIN')
                        {
                            return new Response (
                                //$simpleUser->getSimpleUser($request) ,
                                $simpleUser->getByOrganization($this->getUser()) ,
                                Response::HTTP_OK ,
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
                            "messsage" => "Role admin is required ! " ,
                            
                       ]
                        ); 

                        //return new JsonResponse('role admin is needed');
                    
                
         
        } 


    #[Route('/api/getSimpleUserByType/page/{offset}/of/{limit}',name: 'app_get_simple_user_by_type',methods: ['GET'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to see the  simple users list ! You need to get the admin role .')]
    public function getSimpleUsersByType(
        CoreUserAdditionalService $simpleUser ,
        Request $request ,
        $offset ,
        $limit
        ): Response
            {       $role = $this->getUser()->getCoreUserRoles();
                    foreach ($role as $role)
                        {
                            $roleAdmin = $role->getCoreRole()->getName();
                        }
    
                    if($roleAdmin == 'ROLE_ADMIN')
                        {
                            return new Response (
                                //$simpleUser->getSimpleUser($request) ,
                                $simpleUser->getSimpleUserByType($request,$offset,$limit) ,
                                Response::HTTP_OK ,
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
                            "messsage" => "Role admin is required ! " ,
                            
                       ]
                        ); 

                        //return new JsonResponse('role admin is needed');
                    
                
         
        } 


    #[Route('/api/searchSimpleUser',name: 'app_search_simple_user',methods: ['GET'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to see the  simple users list ! You need to get the admin role .')]
    public function searchSimpleUsers(
        CoreUserAdditionalService $simpleUser ,
        Request $request 
        ): Response
            {      
                $role = $this->getUser()->getCoreUserRoles();
                    foreach ($role as $role)
                        {
                            $roleAdmin = $role->getCoreRole()->getName();
                        }
    
                    if($roleAdmin == 'ROLE_ADMIN')
                        {
                            return new Response (
                                $simpleUser->searchSimpleUser($request,$this->getUser()) ,
                                Response::HTTP_OK ,
                                [] ,
                                [
                                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => 
                                    function($organisation){
                                        return 
                                        'nadia' ;
                                    }
                                ]
                            );
                        }
                    else 
                        return new JsonResponse(['message' => 'role admin is required . try again'],
                        Response::HTTP_INTERNAL_SERVER_ERROR);

        } 



    #[Route('/api/postSimpleUser/{idOrganization}/{idAgency}/{idCountry}/{idRole}',name: 'app_post_simple_user',methods: ['POST'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to add a simple user ! You need to get the admin role .')]
    public function postSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher , 
        $idOrganization ,
        $idAgency ,
        $idCountry ,
        $idRole
    ){
                $role = $this->getUser()->getCoreUserRoles();
                foreach ($role as $role)
                    {
                        $roleAdmin = $role->getCoreRole()->getName();
                    }

                if($roleAdmin == 'ROLE_ADMIN')
                    {
                        return $this->json (
                            $simpleUser->addSimpleUser($request,$validator,$userPasswordHasher,$idOrganization,$idAgency,$idCountry,$idRole) ,
                            Response::HTTP_CREATED ,
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
                    return new JsonResponse(['message' => 'role admin is required . try again'],
                    Response::HTTP_INTERNAL_SERVER_ERROR); 
          
        
    }


    #[Route('/api/putSimpleUser/{idUser}',name: 'app_put_simple_user',methods: ['PUT'])]
    public function putSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        Request $request ,
        $idUser
    ){
                $role = $this->getUser()->getCoreUserRoles();
                foreach ($role as $role)
                    {
                        $roleAdmin = $role->getCoreRole()->getName();
                    }

                if($roleAdmin == 'ROLE_ADMIN')
                    {
                        return $this->json (
                            $simpleUser->editSimpleUser($request,$idUser) ,
                            Response::HTTP_OK ,
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
                    return new JsonResponse(['message' => 'role admin is required . try again'],
                    Response::HTTP_INTERNAL_SERVER_ERROR); 
          
        
    }

    #[Route('/api/changeStatusUser/{idUser}',name: 'app_change_status_simple_user', methods: ['PATCH'])]
    //#[IsGranted('ROLE_ADMIN',message: 'Sorry you are not allowed to enable a simple user account ! You need to get the admin role .')]
    public function changeStatusSimpleUser(
        CoreUserAdditionalService $simpleUser ,
        $idUser ,
        Request $request
    )
    {
                $role = $this->getUser()->getCoreUserRoles();
                foreach ($role as $role)
                    {
                        $roleAdmin = $role->getCoreRole()->getName();
                    }
                    
                if($roleAdmin == 'ROLE_ADMIN')
                    {
                                return $this->json(
                                 $simpleUser->changeStatusUser($idUser,$request) ,
                                 Response::HTTP_OK ,
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
                    return new JsonResponse(['message' => 'role admin is required . try again'],
                    Response::HTTP_INTERNAL_SERVER_ERROR); 
   
    }

    #[Route('/api/myDetails',name: 'app_my_details',methods: ['GET'])]
    public function myDetails()
    {
        
                $token = $this->container->get('security.token_storage')->getToken();
                $user = $token->getUser();
                return  $this->json(
                        $user ,
                        Response::HTTP_OK ,
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

    #[Route('/api/getUserById/{idUser}',name: 'app_user_by_id',methods: ['GET'])]
    public function getUserById(

        CoreUserAdditionalService $simpleUser ,
        $idUser
    )
    {
        
                $role = $this->getUser()->getCoreUserRoles();
                foreach ($role as $role)
                    {
                        $roleAdmin = $role->getCoreRole()->getName();
                    }

                if($roleAdmin == 'ROLE_ADMIN')
                    {
                        return new Response($simpleUser->getSimpleUserById($idUser) ,
                        Response::HTTP_OK ,
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
                    return new JsonResponse(['message' => 'role admin is required . try again'],
                    Response::HTTP_INTERNAL_SERVER_ERROR); 
      
    }

}
