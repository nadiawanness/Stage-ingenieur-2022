<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\CoreAdminAdditionalService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\CoreUser;
use App\Entity\AccessToken;
use App\Repository\CoreUserRepository;
use App\Repository\AccessTokenRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

       return $this->render('security/login.html.twig', 
        ['last_username' => $lastUsername, 'error' => $error]); 
    }

    #[Route(path: '/login/token', name: 'api_login_token' ,methods: ['POST'])]
    public function loginTokenDetails(
        CoreUserRepository $userRepo ,
        AccessTokenRepository $accessRepo ,
        Request $request ,
        JWTTokenManagerInterface $JWTManager ,
        EntityManagerInterface $em 
    )
    {
        $data = json_decode($request->getContent());
        $user = $userRepo->findUserByEmail($data->email);
        if(isset($user))
            {   
                foreach($user as $user)
                    {
                        $userPassword = $user->getPassword();
                    }
                
                if(password_verify($data->password, $userPassword))
                    {
                        $token = $JWTManager->create($user);
                        $access = $accessRepo->findByUser($user->getId());
                        $idUserAccess = 0 ;
                        foreach($access as $access)
                            {
                                $idUserAccess = $access->getCoreUser()->getId();
                            }
                        if($idUserAccess == $user->getId())
                            {
                                $em->getConnection()->beginTransaction();
                                try{
                                    $access->setSingleUseToken($token);
                                    $em->flush();
                                    $em->getConnection()->commit();
                                    return new JsonResponse([
                                        'notice' => 'new token for an old user' ,
                                        'token' => $token ,
                                        'userEmail' => $user->getEmail() ,
                                        'userType' => $user->getType() ,
                                        'token ttl' => time() + 3600
                                    ]);

                                } catch(Exception $e){
                                    $em->getConnection()->rollback();
                                    throw $e;
                                }
                            }
                        else 
                            {
                                $em->getConnection()->beginTransaction();
                                try{
                                    $accessNew = new AccessToken();
                                    $accessNew->setSingleUseToken($token);
                                    $accessNew->setPunchout(false);
                                    $accessNew->setAttributes([
                                        'id' => $user->getId() ,
                                        'username' => $user->getUsername() ,
                                        'email' => $user->getEmail() ,
                                        'type' => $user->getType() ,
                                        'status' => $user->isEnabled() ,
                                        'delegate' => $user->isHasDelegate() ,
                                        'role' => $user->getCoreUserRoles()
                                    ]);
                                    $accessNew->setCoreUser($user);
                                    $em->persist($accessNew);
                                    $em->flush();
                                    $em->getConnection()->commit();
                                    return new JsonResponse([
                                        'notice' => 'new token for a new user' ,
                                        'token' => $token ,
                                        'userEmail' => $user->getEmail() ,
                                        'userType' => $user->getType() ,
                                        'token ttl' => time() + 3600
                                        ]);
            
                                } catch(Exception $e){
                                    $em->getConnection()->rollback();
                                    throw $e;
                                }

                            }

                    }
                else 
                    return new JsonResponse(['message' => 'invalid password . try again'], Response::HTTP_BAD_REQUEST); 
            }
        else 
            return new JsonResponse(['message' => 'invalid credentials . try again'], Response::HTTP_BAD_REQUEST); 
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /* #[Route('/getAdmin',name: 'app_get_admin',methods: ['GET'])]
    public function getAdmins(CoreAdminAdditionalService $admin,Request $request){
       
            return new Response (
                $admin->getAdmin($request)
             );
        
         
    } */

    
   
   
}
