<?php

namespace App\Service;

use App\Entity\CoreCountry;
use App\Entity\CoreRole;
use App\Entity\CoreUser;
use App\Entity\CoreUserRole;
use App\Repository\CoreCountryRepository;
use App\Repository\CoreOrganizationRepository;
use App\Repository\CoreRoleRepository;
use App\Repository\CoreUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoreAdminAdditionalService
{
    public function __construct(
        private CoreUserRepository $userRepo,
        private CoreOrganizationRepository $orgRepo,
        private CoreCountryRepository $countryRepo,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private CoreRoleRepository $roleRepo
    ) {
    }

    /**
     * getAdmin
     * get admin list.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function getAdmin(Request $request)
    {
        $admin = $this->userRepo->findCoreUserByType(CoreUser::TYPE_ADMIN_ADDITIONAL);
        $listAdmin = $this->serializer->serialize($admin, 'json', [
            'groups' => 'coreuser:read',
            'corecountry:read',
            'coreorganization:read',
            'corerole:read',
        ]);

        return new Response($listAdmin, Response::HTTP_OK);
    }

    /**
     * addAdmin
     * post a new admin.
     *
     * @param mixed $request
     * @param mixed $validator
     * @param mixed $userPasswordHasher
     *
     * @return void
     */
    public function addAdmin(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $data = json_decode($request->getContent());
        $this->em->getConnection()->beginTransaction();
        try {
            $user = new CoreUser();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $data->password,
                    $user->getPassword()
                )
            );
            $country = $this->countryRepo->find($data->country);
            if ($country instanceof CoreCountry) {
                if ($country->isEnabled()) {
                    $user->addCoreCountry($country);
                } else {
                    return new JsonResponse(['message' => 'this country is disabled . try again'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this country does not exist . try again'], Response::HTTP_BAD_REQUEST);
            }
            $user->setUsername($data->username);
            $user->setUsernameCanonical($data->usernameCanonical);
            $user->setEmail($data->email);
            $user->setEmailCanonical($data->emailCanonical);
            $user->setCivility($data->civility);
            $user->setType(CoreUser::TYPE_ADMIN_ADDITIONAL);
            $user->setEnabled(true);
            $user->setHasDelegate(false);
            $role = $this->roleRepo->find($data->role);
            if ($role instanceof CoreRole) {
                if ($role->isEnabled()) {
                    $coreUserRole = new CoreUserRole();
                    $coreUserRole->setCoreUser($user);
                    $coreUserRole->setCoreRole($role);
                    $this->em->persist($coreUserRole);
                    $this->em->flush();
                } else {
                    return new JsonResponse(['message' => 'this role is disabled . try again'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this role does not exist . try again'], Response::HTTP_BAD_REQUEST);
            }
            $user->addCoreUserRole($coreUserRole);
            $user->setRoles([]);
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return new Response($errors, Response::HTTP_BAD_REQUEST);
            }
            $this->em->persist($user);
            $this->em->flush();
            $this->em->getConnection()->commit();

            return new JsonResponse([
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'type' => $user->getType(),
                    'civility' => $user->getCivility(),
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getHttpResponse();
        }
    }
}
