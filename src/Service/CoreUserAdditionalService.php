<?php

namespace App\Service;

use App\Entity\CoreAgency;
use App\Entity\CoreCountry;
use App\Entity\CoreOrganization;
use App\Entity\CoreRole;
use App\Entity\CoreUserAgencies;
use App\Entity\CoreUserRole;
use App\Repository\CoreAgencyRepository;
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

class CoreUserAdditionalService
{
    public function __construct(
        private CoreUserRepository $userRepo,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private CoreOrganizationRepository $orgRepo,
        private CoreAgencyRepository $agencyRepo,
        private CoreCountryRepository $countryRepo,
        private CoreRoleRepository $roleRepo
    ) {
    }

    /**
     * getSimpleUser.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function getSimpleUser(Request $request)
    {
        $user = $this->userRepo->findCoreUserByType(CoreUser::TYPE_USER_ADDITIONAL);
        $p = $this->serializer->serialize(
            $user,
            'json',
            [
                'groups' => 'coreuser:read',
                'corecountry:read',
                'coreorganization:read',
                'corerole:read',
            ]
        );

        return $p;
    }

    /**
     * searchSimpleUser.
     *
     * @param mixed $admin
     * @param mixed $request
     *
     * @return void
     */
    public function searchSimpleUser($admin, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $searchUser = $this->userRepo->findByOrg($admin, true, $data);
        if (false == empty($searchUser)) {
            // $listSearched = $this->serializer->serialize(
            //     $searchUser,
            //     'json',
            //     [
            //         'groups' =>
            //             'coreuser:read' ,
            //             'corecountry:read' ,
            //             'coreorganization:read' ,
            //             'corerole:read'
            //     ]
            // );

            return new JsonResponse([
                'totalItems' => sizeof($searchUser),
                'data' => [],
                ]);
        } else {
            return new JsonResponse([
               'totalItems' => sizeof($searchUser),
               'data' => [],
               ]);
        }
    }

    /**
     * getByOrganization.
     *
     * @return void
     */
    public function getByOrganization()
    {
        $user = $this->userRepo->findUserByOrg();
        dd($user);
        $listUser = $this->serializer->serialize(
            $user,
            'json',
            [
            'groups' => 'coreuser:read',
                'corecountry:read',
                'coreorganization:read',
                'corerole:read',
        ]
        );
        dd($listUser);

        return $listUser;
    }

    /**
     * addSimpleUser.
     *
     * @param mixed $request
     * @param mixed $validator
     * @param mixed $userPasswordHasher
     *
     * @return void
     */
    public function addSimpleUser(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $data = json_decose($request->getContent());
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
            $user->setType(CoreUser::TYPE_USER_ADDITIONAL);
            $country = $this->countryRepo->find($adat->country);
            if ($country instanceof CoreCountry) { // step1 : verify the existance of the country in CoreCountry
                if ($country->isEnabled()) { // step2 : verify if the country is enabled or not
                    $user->addCoreCountry($country);
                } else {
                    return new JsonResponse(['message' => 'this country is disabled .'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this country does not exist .'], Response::HTTP_BAD_REQUEST);
            }

            $organization = $this->orgRepo->find($data->organization);

            if ($organization instanceof CoreOrganization) { // step1 : verify the existance of $organization in CoreOrganization
                if ($organization->isEnabled()) { // step2 : verify if the organisation is enabled or not
                    if ('valid' == $organization->getStatus()) { // step3 : verify if the organisation status (valid or not)
                        $user->addOrganization($organization);
                    } else {
                        return new JsonResponse(['message' => 'this organization is not valid .'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this organization is disabled .'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this organization does not exist .'], Response::HTTP_BAD_REQUEST);
            }
            $agency = $this->agencyRepo->find($data->agency);
            if ($agency instanceof CoreAgency) { // step1 : verify the existance of the agency
                if ($agency->getCoreOrganization()->getId() == $organization->getId()) { // verify if the agency exist in the chosen organization
                    if ($agency->isEnabled()) { // verify if the agency is enabled or not
                        $coreUserAgency = new CoreUserAgencies();
                        $coreUserAgency->setCoreUser($user);
                        $coreUserAgency->setCoreAgency($agency);
                        $this->em->persist($coreUserAgency);
                    } else {
                        return new JsonResponse(['message' => 'this agency is disabled .'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this agency does not belong to the chosen organisation .'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this agency does not exist .'], Response::HTTP_BAD_REQUEST);
            }

            $role = $this->roleRepo->find($data->role);
            if ($role instanceof CoreRole) { // step1 : verify the existance of the role
                if ($role->isEnabled()) { // verify if the agency is enabled or not
                    $coreUserRole = new CoreUserRole();
                    $coreUserRole->setCoreUser($user);
                    $coreUserRole->setCoreRole($role);
                    $this->em->persist($coreUserRole);
                    $this->em->flush();
                } else {
                    return new JsonResponse(['message' => 'this role is disabled .'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this role does not exist .'], Response::HTTP_BAD_REQUEST);
            }

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

    /**
     * editSimpleUser.
     *
     * @param mixed $request
     * @param mixed $idUser
     *
     * @return void
     */
    public function editSimpleUser(Request $request, $idUser)
    {
        $user = $this->userRepo->find($idUser);
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found .try again !'], Response::HTTP_BAD_REQUEST);
        }
        $donnees = json_decode($request->getContent());
        if ('core_user_additional' == $user->getType()) {
            $this->em->getConnection()->beginTransaction();
            try {
                setAttributes(
                    $user,
                    $donnees->username,
                    $donnees->usernameCanonical,
                    $donnees->email,
                    $donnees->emailCanonical,
                    $donnees->salt,
                    $donnees->locale,
                    $donnees->firstName,
                    $donnees->lastName,
                    $donnees->functionUser,
                    $donnees->phone,
                    $donnees->civility,
                    $donnees->idErp
                );
                $user->setUpdatedAt(new \DateTimeImmutable());

                $this->em->flush();
                $this->em->getConnection()->commit();

                return $user;
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                throw $e->getHttpResponse();
            }
        } else {
            return new JsonResponse(
                ['message' => 'must be of type core_user_additional . try again '],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * setAttributes.
     *
     * @param mixed $user
     * @param mixed $username
     * @param mixed $usernameCanonical
     * @param mixed $email
     * @param mixed $emailCanonical
     * @param mixed $salt
     * @param mixed $locale
     * @param mixed $firstName
     * @param mixed $lastName
     *
     * @return void
     */
    public function setAttributes(
        CoreUser $user,
        $username,
        $usernameCanonical,
        $email,
        $emailCanonical,
        $salt,
        $locale,
        $firstName,
        $lastName,
        $functionUser,
        $phone,
        $civility,
        $idErp
    ) {
        $user->setUsername($username);
        $user->setUsernameCanonical($usernameCanonical);
        $user->setEmail($email);
        $user->setEmailCanonical($emailCanonical);
        $user->setSalt($salt);
        $user->setLocale($locale);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setFunctionUser($functionUser);
        $user->setPhone($phone);
        $user->setCivility($civility);
        $user->setIdErp($idErp);
    }

    /**
     * getSimpleUserById.
     *
     * @param mixed $idUser
     *
     * @return void
     */
    public function getSimpleUserById($idUser)
    {
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try {
            if ($user instanceof CoreUser) {
                if ('core_user_additional' == $user->getType()) {
                    $p = $this->serializer->serialize($user, 'json');

                    return $p;
                } else {
                    return new JsonResponse(
                        ['message' => 'this user should be core_user_additional type .'],
                        Response::HTTP_BAD_REQUEST
                    );
                }
            } else {
                return new JsonResponse(['message' => 'this user does not exist .'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getHttpResponse();
        }
    }

    /**
     * changeStatusUser
     * enable or disable simple users.
     *
     * @param mixed $idUser
     * @param mixed $request
     *
     * @return void
     */
    public function changeStatusUser($idUser, Request $request)
    {
        $user = $this->userRepo->find($idUser);
        $data = json_decode($request->getContent());
        $this->em->getConnection()->beginTransaction();
        try {
            if ($user instanceof CoreUser) {
                if ('core_user_additional' == $user->getType()) {
                    if ($user->isEnabled()) { // verify if the user account is enabled
                        if (false == $data->enabled | 0 == $data->enabled) { // verify if the value passed in the request is false or 0
                            $user->setEnabled($donnees->enabled); // disable the user
                            $this->em->flush();
                            $this->em->getConnection()->commit();

                            return 'user is disabled ! ';
                        } else {
                            return new JsonResponse(['message' => 'boolean value is required or is already enabled . try again'], Response::HTTP_BAD_REQUEST);
                        }
                    } elseif (!$user->isEnabled()) {
                        if (true == $donnees->enabled | 1 == $donnees->enabled) { // verify if the value passed in the request is true or 1
                            $user->setEnabled($donnees->enabled); // enable the user
                            $this->em->flush();
                            $this->em->getConnection()->commit();

                            return 'user is enabled ! ';
                        } else {
                            return new JsonResponse(['message' => 'boolean value is required or is already disabled . try again'], Response::HTTP_BAD_REQUEST);
                        }
                    } else {
                        return new JsonResponse(['message' => 'error . try again'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this user should be a core_user_additional type .'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this user does not exist .'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getHttpResponse();
        }
    }
}
