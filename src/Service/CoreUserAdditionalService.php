<?php

namespace App\Service;

use App\Entity\CoreAgency;
use App\Entity\CoreCountry;
use App\Entity\CoreOrganization;
use App\Entity\CoreRole;
use App\Entity\CoreUser;
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
     * getSimpleUser
     * list simple users who are assigned to the same organization as the connected admin.
     *
     * @param mixed $request
     * @param mixed $admin
     *
     * @return void
     */
    public function getSimpleUser(Request $request, $admin)
    {
        $user = $this->userRepo->findCoreUserByOrg($admin);
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

        return new Response($listUser, Response::HTTP_OK);
    }

    /**
     * searchSimpleUser.
     * search simple users.
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
        dd($searchUser);
        $listSearched = $this->serializer->serialize(
            $searchUser,
            'json',
            [
                'groups' => 'coreuser:read',
                    'corecountry:read',
                    'coreorganization:read',
                    'corerole:read',
            ]
        );
        dd($listSearched);
        if (true == empty($searchUser)) {
            return new JsonResponse([
                'totalItems' => 0,
                'data' => [],
                ]);
        } else {
            return new JsonResponse([
               'totalItems' => sizeof($searchUser),
               'data' => $listSearched,
               ]);
        }
    }

    /**
     * addSimpleUser.
     * add new simple user.
     *
     * @param mixed $request
     * @param mixed $validator
     * @param mixed $userPasswordHasher
     *
     * @return void
     */
    public function addSimpleUser(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
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
            $user->setUsername($data->username);
            $user->setUsernameCanonical($data->username);
            $user->setEmail($data->email);
            $user->setEmailCanonical($data->email);
            $user->setCivility($data->civility);
            $user->setType(CoreUser::TYPE_USER_ADDITIONAL);
            $country = $this->countryRepo->find($data->country);
            if ($country instanceof CoreCountry) { // step1 : verify the existance of the country in CoreCountry
                if ($country->isEnabled()) { // step2 : verify if the country is enabled or not
                    $user->addCoreCountry($country);
                } else {
                    return new JsonResponse(['message' => 'this country is disabled.'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this country does not exist.'], Response::HTTP_BAD_REQUEST);
            }

            $organization = $this->orgRepo->find($data->organization);

            if ($organization instanceof CoreOrganization) { // step1 : verify the existance of $organization in CoreOrganization
                if ($organization->isEnabled()) { // step2 : verify if the organisation is enabled or not
                    if ('valid' == $organization->getStatus()) { // step3 : verify if the organisation status (valid or not)
                        $user->addOrganization($organization);
                    } else {
                        return new JsonResponse(['message' => 'this organization is not valid.'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this organization is disabled.'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this organization does not exist.'], Response::HTTP_BAD_REQUEST);
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
                        return new JsonResponse(['message' => 'this agency is disabled.'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this agency does not belong to the chosen organisation.'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this agency does not exist.'], Response::HTTP_BAD_REQUEST);
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
                    return new JsonResponse(['message' => 'this role is disabled.'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this role does not exist.'], Response::HTTP_BAD_REQUEST);
            }
            $user->setRoles([]);
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return new Response($errors, Response::HTTP_BAD_REQUEST);
            }

            $this->em->persist($user);
            $this->em->flush();
            $this->em->getConnection()->commit();

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
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getHttpResponse();
        }
    }

    /**
     * editSimpleUser.
     * edit an existing simple user.
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
            return new JsonResponse(['message' => 'User not found.try again!'], Response::HTTP_BAD_REQUEST);
        }
        $data = json_decode($request->getContent());
        if ('core_user_additional' == $user->getType()) {
            $this->em->getConnection()->beginTransaction();
            try {
                $user->setUsername($data->username);
                $user->setUsernameCanonical($data->username);
                $user->setEmail($data->email);
                $user->setEmailCanonical($data->email);
                $user->setLocale($data->locale);
                $user->setFirstName($data->firstName);
                $user->setLastName($data->lastName);
                $user->setFunctionUser($data->functionUser);
                $user->setPhone($data->phone);
                $user->setCivility($data->civility);
                $user->setIdErp($data->idErp);
                $user->setUpdatedAt(new \DateTimeImmutable());

                $this->em->flush();
                $this->em->getConnection()->commit();

                return new JsonResponse([
                    'userId' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'locale' => $user->getLocale(),
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'function_user' => $user->getFunctionUser(),
                    'phone' => $user->getPhone(),
                    'civility' => $user->getCivility(),
                    'id_erp' => $user->getIdErp(),
                    'updated_at' => $user->getUpdatedAt(),
                ]);
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                throw $e->getHttpResponse();
            }
        } else {
            return new JsonResponse(
                ['message' => 'must be of type core_user_additional.try again '],
                Response::HTTP_BAD_REQUEST
            );
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
                            $user->setEnabled($data->enabled); // disable the user
                            $this->em->flush();
                            $this->em->getConnection()->commit();

                            return new JsonResponse([
                                'id' => $user->getId(),
                                'email' => $user->getEmail(),
                                'enabled' => $user->isEnabled(),
                            ], Response::HTTP_OK);
                        } else {
                            return new JsonResponse(['message' => 'boolean value is required or is already enabled.try again'], Response::HTTP_BAD_REQUEST);
                        }
                    } elseif (!$user->isEnabled()) {
                        if (true == $data->enabled | 1 == $data->enabled) { // verify if the value passed in the request is true or 1
                            $user->setEnabled($data->enabled); // enable the user
                            $this->em->flush();
                            $this->em->getConnection()->commit();

                            return new JsonResponse([
                                'id' => $user->getId(),
                                'email' => $user->getEmail(),
                                'enabled' => $user->isEnabled(),
                            ], Response::HTTP_OK);
                        } else {
                            return new JsonResponse(['message' => 'boolean value is required or is already disabled.try again'], Response::HTTP_BAD_REQUEST);
                        }
                    } else {
                        return new JsonResponse(['message' => 'error.try again'], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse(['message' => 'this user should be a core_user_additional type.'], Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse(['message' => 'this user does not exist.'], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getHttpResponse();
        }
    }
}
