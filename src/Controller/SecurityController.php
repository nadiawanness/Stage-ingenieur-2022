<?php

namespace App\Controller;

use App\Entity\AccessToken;
use App\Repository\AccessTokenRepository;
use App\Repository\CoreUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]
        );
    }

    /**
     * loginTokenDetails
     * generate tokens.
     *
     * @param mixed $userRepo
     * @param mixed $accessRepo
     * @param mixed $request
     * @param mixed $JWTManager
     * @param mixed $em
     *
     * @return void
     */
    #[Route(path: '/login/token', name: 'api_login_token', methods: ['POST'])]
    public function loginTokenDetails(
        CoreUserRepository $userRepo,
        AccessTokenRepository $accessRepo,
        Request $request,
        JWTTokenManagerInterface $JWTManager,
        EntityManagerInterface $em
    ) {
        $data = json_decode($request->getContent());
        $user = $userRepo->findUserByEmail($data->email);
        if (isset($user)) {
            foreach ($user as $user) {
                $userPassword = $user->getPassword();
                $userType = $user->getType();
            }
            if (password_verify($data->password, $userPassword)) {
                $token = $JWTManager->create($user);
                $access = $accessRepo->findByUser($user->getId());
                $idUserAccess = 0;
                foreach ($access as $access) {
                    $idUserAccess = $access->getCoreUser()->getId();
                }
                if ($idUserAccess == $user->getId()) {
                    $em->getConnection()->beginTransaction();
                    try {
                        $access->setSingleUseToken($token);
                        $em->flush();
                        $em->getConnection()->commit();
                        // dd($userType);
                        if ('core_admin_additional' == $user->getType()) {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'role_admin' => true,
                                'role_super_admin' => false,
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        } elseif ('core_user_additional' == $user->getType()) {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        } else {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'role_admin' => false,
                                'role_super_admin' => true,
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        }
                    } catch (Exception $e) {
                        $em->getConnection()->rollback();
                        throw $e;
                    }
                } else {
                    $em->getConnection()->beginTransaction();
                    try {
                        $accessNew = new AccessToken();
                        $accessNew->setSingleUseToken($token);
                        $accessNew->setPunchout(false);
                        $accessNew->setAttributes([
                            'id' => $user->getId(),
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'type' => $user->getType(),
                            'status' => $user->isEnabled(),
                            'delegate' => $user->isHasDelegate(),
                            'role' => $user->getCoreUserRoles(),
                        ]);
                        $accessNew->setCoreUser($user);
                        $em->persist($accessNew);
                        $em->flush();
                        $em->getConnection()->commit();

                        if ('core_admin_additional' == $user->getType()) {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'role_admin' => true,
                                'role_super_admin' => false,
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        } elseif ('core_user_additional' == $user->getType()) {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        } else {
                            return new JsonResponse([
                                'access token' => $token,
                                'email' => $user->getEmail(),
                                'expire_in' => time() + 3600,
                                'first_name' => $user->getFirstName(),
                                'last_name' => $user->getLastName(),
                                'locale' => $user->getLocale(),
                                'organizations' => [],
                                'role_admin' => false,
                                'role_super_admin' => true,
                                'token_type' => 'bearer',
                                'user_id' => $user->getId(),
                                ]);
                        }
                    } catch (Exception $e) {
                        $em->getConnection()->rollback();
                        throw $e->getHttpResponse();
                    }
                }
            } else {
                return new JsonResponse(['message' => 'invalid password.try again'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse(['message' => 'invalid credentials.try again'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
