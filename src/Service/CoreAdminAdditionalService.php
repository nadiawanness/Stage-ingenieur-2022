<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreUserRepository;
use App\Repository\CoreOrganizationRepository;
use App\Repository\CoreCountryRepository;
use App\Repository\CoreRoleRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use App\Entity\CoreCountry; 
use App\Entity\CoreOrganization;
use App\Entity\CoreRole;
use App\Entity\CoreUserRole; 
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoreAdminAdditionalService 
{
    public function __construct(
        private CoreUserRepository $userRepo ,
        private CoreOrganizationRepository $orgRepo ,
        private CoreCountryRepository $countryRepo ,
        private SerializerInterface $serializer , 
        private EntityManagerInterface $em ,
        private PaginatorInterface $paginator ,
        private CoreRoleRepository $roleRepo
        )
    {

    }

    public function getAdmin(Request $request){

        $admin = $this->userRepo->findCoreUserByType('core_admin_additional');
        $pagination = $this->paginator->paginate(
            $admin, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );
        $p = $this->serializer->serialize($pagination, 'json',[ 
        'groups' =>
            'coreuser:read' ,
            'corecountry:read' , 
            'coreorganization:read' , 
            'corerole:read'
    ]);
        return $p;
        
    }

    public function addAdmin(
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher ,
        $idOrg ,
        $idCountry ,
        $idRole
      )
    {
       $jsonRecu = $request->getContent();
       $this->em->getConnection()->beginTransaction();
       try{
        $user = $this->serializer->deserialize($jsonRecu, CoreUser::class,'json');
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );

        $country = $this->countryRepo->find($idCountry);
        //dd($idCountry);
        if($country instanceof CoreCountry)
            {
                if($country->isEnabled())
                    {
                        $user->addCoreCountry($country);
                    }
                else 
                    return new JsonResponse(['message' => 'this country is disabled . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        else 
            return new JsonResponse(['message' => 'this country does not exist . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);

        $organization = $this->orgRepo->find($idOrg);
        //dd($idOrg);
        if($organization instanceof CoreOrganization)
            {
                if($organization->isEnabled())
                    {
                        if($organization->getStatus() == 'valid')
                            {
                                $user->addCoreOrganization($organization);
                            }
                        else 
                            return new JsonResponse(['message' => 'this organization is not valid . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
                    }
                else 
                    return new JsonResponse(['message' => 'this organization is not enabled . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        else 
            return new JsonResponse(['message' => 'this organization does not exist . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
            $user->setType('core_admin_additional');
            $user->setEnabled(true);
            $user->setHasDelegate(false);

        $role = $this->roleRepo->find($idRole);
        if($role instanceof CoreRole)
            {
                if($role->isEnabled())
                    {
                        $coreUserRole = new CoreUserRole();
                        $coreUserRole->setCoreUser($user);
                        $coreUserRole->setCoreRole($role);
                        $this->em->persist($coreUserRole);
                        $this->em->flush();
                    }
                else 
                    return new JsonResponse(['message' => 'this role is disabled . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);
            } 
        else 
            return new JsonResponse(['message' => 'this role does not exist . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);


        $user->addCoreUserRole($coreUserRole);
        $user->setRoles([]);
        //dd($user->getCoreCountries()->getId());
        //$user->setConfirmationToken(md5(uniqid()));

        $errors = $validator->validate($user);
        if(count($errors) > 0)
        {

            return new Response($errors,400);

        }

        $this->em->persist($user);
        $this->em->flush();
        $this->em->getConnection()->commit();
        return $user;

        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }

       
    }
} 