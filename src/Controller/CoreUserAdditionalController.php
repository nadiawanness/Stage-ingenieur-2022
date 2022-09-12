<?php

namespace App\Controller;

use App\Service\CoreUserAdditionalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoreUserAdditionalController extends AbstractController
{
    /**
     * getSimpleUsers
     * get list of simple users.
     *
     * @param mixed $simpleUser
     * @param mixed $request
     *
     * @return void
     */
    #[Route('/api/getSimpleUser', name: 'app_get_simple_user', methods: ['GET'])]
    public function getSimpleUsers(CoreUserAdditionalService $simpleUser, Request $request)
    {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }
        if ('ROLE_ADMIN' == $roleAdmin) {
            return $simpleUser->getSimpleUser($request, $this->getUser());
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required.try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * searchSimpleUsers
     * search users.
     *
     * @param mixed $simpleUser
     * @param mixed $request
     *
     * @return void
     */
    #[Route('/api/searchSimpleUser', name: 'app_search_simple_user', methods: ['GET'])]
    public function searchSimpleUsers(CoreUserAdditionalService $simpleUser, Request $request)
    {
        $role = $this->getUser()->getCoreUserRoles();
        foreach ($role as $role) {
            $roleAdmin = $role->getCoreRole()->getName();
        }
        if ('ROLE_ADMIN' == $roleAdmin) {
            return $simpleUser->searchSimpleUser($this->getUser(), $request);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required.try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * postSimpleUser
     * add a new simple user.
     *
     * @param mixed $simpleUser
     * @param mixed $request
     * @param mixed $validator
     * @param mixed $userPasswordHasher
     *
     * @return void
     */
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
                ['message' => 'role admin is required.try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * putSimpleUser
     * modify an existing simple user.
     *
     * @param mixed $simpleUser
     * @param mixed $request
     * @param mixed $idUser
     *
     * @return void
     */
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
            return $simpleUser->editSimpleUser($request, $idUser);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required.try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * changeStatusSimpleUser
     * enable or disable an existing simple user.
     *
     * @param mixed $simpleUser
     * @param mixed $request
     * @param mixed $idUser
     *
     * @return void
     */
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
            return $simpleUser->changeStatusUser($idUser, $request);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required.try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * getDetails
     * get details of the connected user.
     *
     * @return void
     */
    #[Route('/api/getDetails', name: 'app_get_details', methods: ['GET'])]
    public function getDetails()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $user = $token->getUser();

        return new JsonResponse(
            [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'civility' => $user->getCivility(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'phone' => $user->getPhone(),
            'locale' => $user->getLocale(),
            ],
            Response::HTTP_OK
        );
    }
}
