<?php

namespace App\Controller;

use App\Service\CoreUserAdditionalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoreUserAdditionalController extends AbstractController
{
    #[Route('/core/user/additional', name: 'app_core_user_additional')]
    public function index(): Response
    {
        return $this->render('core_user_additional/index.html.twig', [
            'controller_name' => 'CoreUserAdditionalController',
        ]);
    }

    #[Route('/api/getSimpleUser', name: 'app_get_simple_user', methods: ['GET'])]
    public function getSimpleUsers(CoreUserAdditionalService $simpleUser, Request $request)
    {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return new Response(
                $simpleUser->getByOrganization(),
                Response::HTTP_OK,
                [],
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                        return 'nadia';
                    },
                ]
            );
        } else {
            return $this->json(
                [
               'code' => '400',
               'messsage' => 'Role admin is required ! ',
                       ]
            );
        }
    }

    #[Route('/api/getSimpleUserByType', name: 'app_get_simple_user_by_type', methods: ['GET'])]
    public function getSimpleUsersByType(
        CoreUserAdditionalService $simpleUser,
        Request $request,
        /* $offset ,
        $limit */
    ): Response {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return new Response(
                $simpleUser->getSimpleUserByType($this->getUser(), $request /* ,$offset,$limit */),
                Response::HTTP_OK,
                [],
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                        return 'nadia';
                    },
                ]
            );
        } else {
            return $this->json(
                [
               'code' => '400',
               'messsage' => 'Role admin is required ! ',
                       ]
            );
        }
    }

    #[Route('/api/searchSimpleUser', name: 'app_search_simple_user', methods: ['GET'])]
    public function searchSimpleUsers(
        CoreUserAdditionalService $simpleUser,
        Request $request
    ): Response {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return $simpleUser->searchSimpleUser($this->getUser(), $request);
            /* return new Response (
                $simpleUser->searchSimpleUser($this->getUser(),$request) ,
                Response::HTTP_OK ,
                [] ,
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER =>
                    function($organisation){
                        return
                        'nadia' ;
                    }
                ]
            ); */
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/postSimpleUser', name: 'app_post_simple_user', methods: ['POST'])]
    public function postSimpleUser(CoreUserAdditionalService $simpleUser, Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }
        if ('ROLE_ADMIN' == $roleAdmin) {
            return $simpleUser->addSimpleUser($request, $validator, $userPasswordHasher);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/putSimpleUser/{idUser}', name: 'app_put_simple_user', methods: ['PUT'])]
    public function putSimpleUser(
        CoreUserAdditionalService $simpleUser,
        Request $request,
        $idUser
    ) {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return $this->json(
                $simpleUser->editSimpleUser($request, $idUser),
                Response::HTTP_OK,
                [],
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                    },
                ]
            );
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/changeStatusUser/{idUser}', name: 'app_change_status_simple_user', methods: ['PATCH'])]
    public function changeStatusSimpleUser(
        CoreUserAdditionalService $simpleUser,
        $idUser,
        Request $request
    ) {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return $this->json(
                $simpleUser->changeStatusUser($idUser, $request),
                Response::HTTP_OK,
                [],
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                        return 'nadia';
                    },
                ]
            );
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/myDetails', name: 'app_my_details', methods: ['GET'])]
    public function myDetails()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $user = $token->getUser();

        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            [
                ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                    return 'nadia';
                },
            ]
        );
    }

    #[Route('/api/getUserById/{idUser}', name: 'app_user_by_id', methods: ['GET'])]
    public function getUserById(
        CoreUserAdditionalService $simpleUser,
        $idUser
    ) {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }

        if ('ROLE_ADMIN' == $roleAdmin) {
            return new Response(
                $simpleUser->getSimpleUserById($idUser),
                Response::HTTP_OK,
                [],
                [
                    ObjectNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($organisation) {
                        return 'nadia';
                    },
                ]
            );
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
