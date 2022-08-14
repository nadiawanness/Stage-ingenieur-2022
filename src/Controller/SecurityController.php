<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\CoreAdminAdditionalService;
use Symfony\Component\HttpFoundation\Request;

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

    
    #[Route(path: '/api/login', name: 'app_login_api',methods: ['GET'])]
    public function tokenDetails(): Response
    {
         return new Response(
           
            'message'
         );
    }

   
}
