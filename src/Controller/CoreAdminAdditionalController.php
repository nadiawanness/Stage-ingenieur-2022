<?php

namespace App\Controller;

use App\Service\CoreAdminAdditionalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoreAdminAdditionalController extends AbstractController
{
    #[Route('/api/getAdmin', name: 'app_get_admin', methods: ['GET'])]
    /**
     * getAdmins
     * list admins.
     * get list of admins.
     *
     * @param mixed $admin
     * @param mixed $request
     */
    public function getAdmins(CoreAdminAdditionalService $admin, Request $request): Response
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return $admin->getAdmin($request);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/postAdmin', name: 'app_post_admin', methods: ['POST'])]
    /**
     * postAdmin.
     * post a new admin.
     *
     * @param mixed $admin
     * @param mixed $request
     * @param mixed $validator
     * @param mixed $userPasswordHasher
     *
     * @return void
     */
    public function postAdmin(CoreAdminAdditionalService $admin, Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return $admin->addAdmin($request, $validator, $userPasswordHasher);
        } else {
            return new JsonResponse(
                ['message' => 'role admin is required . try again'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
