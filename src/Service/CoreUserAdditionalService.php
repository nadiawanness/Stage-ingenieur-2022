<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreUserRepository;
use App\Repository\CoreOrganizationRepository;
use App\Repository\CoreAgencyRepository;
use App\Repository\CoreCountryRepository;
use App\Repository\CoreRoleRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use App\Entity\CoreUserAgencies; 
use App\Entity\CoreCountry;
use App\Entity\CoreOrganization;
use App\Entity\CoreRole;
use App\Entity\CoreAgency;
use App\Entity\CoreUserRole;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoreUserAdditionalService 
{
    public function __construct(
        private CoreUserRepository $userRepo ,
        private SerializerInterface $serializer , 
        private EntityManagerInterface $em ,
        private PaginatorInterface $paginator ,
        private CoreOrganizationRepository $orgRepo ,
        private CoreAgencyRepository $agencyRepo ,
        private CoreCountryRepository $countryRepo ,
        private CoreRoleRepository $roleRepo
        )
    {

    }

    public function getSimpleUser(Request $request){

        $admin = $this->userRepo->findCoreUserByType('core_user_additional');
        $pagination = $this->paginator->paginate(
            $admin, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );
        $p = $this->serializer->serialize($pagination, 'json',
            [ 
                'groups' =>
                    'coreuser:read' ,
                    'corecountry:read' , 
                    'coreorganization:read' , 
                    'corerole:read'
            ]
    );
        return $p;
        
    }

    /* public function searchSimpleUser(Request $request ,
    $emailUser
    ){

        $user = $this->userRepo->searchByEmail($emailUser);
            foreach($user as $user)
                {
                    $type = $user->getType();
                }

                //dd($type);

            if( $type == 'core_user_additional' )
                    {
                        $pagination = $this->paginator->paginate(
                            $user, // query NOT result
                            $request->query->getInt('page', 1) , // page number
                            3 // limit per page
                        );
                        $p = $this->serializer->serialize($pagination, 'json',
                            [ 
                                'groups' =>
                                    'coreuser:read' ,
                                    'corecountry:read' , 
                                    'coreorganization:read' , 
                                    'corerole:read'
                            ]
                        );
                            return $p;
                    }
                else 
                    return new JsonResponse(['message' => 'user type must be core_user_additional . try again .'], Response::HTTP_NOT_FOUND);
           
        
        
    } */

    public function searchSimpleUser(Request $request , 
    CoreUser $admin
    ){
        $donnees = json_decode($request->getContent());
        //dd($donnees);
        $user = $this->userRepo->findByOrg($admin,$donnees->email,true);
        dd($user);
            foreach($user as $user)
                {
                    $type = $user->getType();
                }

            if( $type == 'core_user_additional' )
                    {
                        $pagination = $this->paginator->paginate(
                            $user, // query NOT result
                            $request->query->getInt('page', 1) , // page number
                            3 // limit per page
                        );
                        $p = $this->serializer->serialize($pagination, 'json',
                            [ 
                                'groups' =>
                                    'coreuser:read' ,
                                    'corecountry:read' , 
                                    'coreorganization:read' , 
                                    'corerole:read'
                            ]
                        );
                            return $p;
                    }
                else 
                    return new JsonResponse(['message' => 'user type must be core_user_additional . try again .'], Response::HTTP_NOT_FOUND);
           
        
        
    }

    public function getByOrganization($admin)
    {
        $user = $this->userRepo->findByOrg($admin,null,false);
        //dd($user);
        $p = $this->serializer->serialize($user , 'json',[ 
            'groups' =>
                'coreuser:read' ,
                'corecountry:read' , 
                'coreorganization:read' , 
                'corerole:read'
        ]
    );
        return $p;
    }
  

    public function addSimpleUser(
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher ,
        $idOrganization ,
        $idAgency ,
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
        $user->setType('core_user_additional');
         
        //$user->setEnabled(true);
        //$user->setHasDelegate(false);

        $country = $this->countryRepo->find($idCountry);
        if($country instanceof CoreCountry) //step1 : verify the existance of the country in CoreCountry
            {
                if($country->isEnabled()) //step2 : verify if the country is enabled or not
                        {
                            $user->addCoreCountry($country);
                        }
                    else 
                        return new JsonResponse(['message' => 'this country is disabled .'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        else 
            return new JsonResponse(['message' => 'this country does not exist .'], Response::HTTP_BAD_REQUEST);
      

        $organization  = $this->orgRepo->find($idOrganization);
        
            if($organization instanceof CoreOrganization) //step1 : verify the existance of $organization in CoreOrganization
                {
                    if($organization->isEnabled()) //step2 : verify if the organisation is enabled or not 
                        {
                            if($organization->getStatus() == 'valid') //step3 : verify if the organisation status (valid or not) 
                                {
                                    $user->addOrganization($organization);
                                }
                            else 
                                return new JsonResponse(['message' => 'this organization is not valid .'], Response::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    else 
                        return new JsonResponse(['message' => 'this organization is disabled .'], Response::HTTP_INTERNAL_SERVER_ERROR);

                }
            else 
                return new JsonResponse(['message' => 'this organization does not exist .'], Response::HTTP_BAD_REQUEST);
        
                
        $agency = $this->agencyRepo->find($idAgency);
        if($agency instanceof CoreAgency) //step1 : verify the existance of the agency
            {
                if($agency->getCoreOrganization()->getId() == $organization->getId()) //verify if the agency exist in the chosen organization
                    {
                        if($agency->isEnabled()) //verify if the agency is enabled or not
                            {
                                $coreUserAgency = new CoreUserAgencies();
                                $coreUserAgency->setCoreUser($user);    
                                $coreUserAgency->setCoreAgency($agency);
                                $this->em->persist($coreUserAgency);
                            }
                        else 
                            return new JsonResponse(['message' => 'this agency is disabled .'], Response::HTTP_INTERNAL_SERVER_ERROR);

                    
                    }
                else 
                    return new JsonResponse(['message' => 'this agency does not belong to the chosen organisation .'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        else 
            return new JsonResponse(['message' => 'this agency does not exist .'], Response::HTTP_BAD_REQUEST);
        
        
        $role = $this->roleRepo->find($idRole);
        dd($role);
        if($role instanceof CoreRole) //step1 : verify the existance of the role
            {
                if($role->isEnabled()) //verify if the agency is enabled or not
                    {
                        $coreUserRole = new CoreUserRole();
                        $coreUserRole->setCoreUser($user);    
                        $coreUserRole->setCoreRole($role);
                        $this->em->persist($coreUserRole);
                        $this->em->flush();
                    }
                else 
                    return new JsonResponse(['message' => 'this role is disabled .'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        else 
            return new JsonResponse(['message' => 'this role does not exist .'], Response::HTTP_BAD_REQUEST);

            $user->setRoles([]);
            

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

    public function editSimpleUser(
        Request $request ,
        $idUser 
    )
    {
        $user = $this->userRepo->find($idUser);
        //dd($user instanceof CoreUser);

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found .try again !'], Response::HTTP_BAD_REQUEST);
        }
                $donnees = json_decode($request->getContent());
                if($user->getType() == 'core_user_additional')
                    {
                        $this->em->getConnection()->beginTransaction();
                        try {
                                /* $user->setUsername($donnees->username);
                                $user->setUsernameCanonical($donnees->usernameCanonical);
                                $user->setEmail($donnees->email);
                                $user->setEmailCanonical($donnees->emailCanonical);
                                $user->setSalt($donnees->salt);
                                //$user->setLastLogin($donnees->lastLogin);
                                //$user->setConfirmationToken($donnees->confirmationToken);
                                //$user->setPasswordRequestedAt($donnees->passwordRequestedAt);
                                $user->setLocale($donnees->locale);
                                $user->setFirstName($donnees->firstName);
                                $user->setLastName($donnees->lastName);
                                $user->setFunctionUser($donnees->functionUser);
                                $user->setPhone($donnees->phone);
                                $user->setCivility($donnees->civility);
                                //$user->setType($donnees->type);
                                $user->setIdErp($donnees->idErp);
                                //$user->setConfirmPassword($donnees->confirmPassword);
                                //$user->setEnabled($donnees->enabled);
                                //$user->setHasDelegate($donnees->hasDelegate);
                                $user->setUpdatedAt(new \DateTimeImmutable()); */

                                setAttributes($user,$donnees->username,$donnees->usernameCanonical,$donnees->email,$donnees->emailCanonical,
                                $donnees->salt,$donnees->locale,$donnees->firstName,$donnees->lastName,$donnees->functionUser,$donnees->phone,
                                $donnees->civility,$donnees->idErp);
                                $user->setUpdatedAt(new \DateTimeImmutable());
                            

                                $this->em->flush();
                                $this->em->getConnection()->commit();
                                return $user;

                            } catch(Exception $e){
                                $em->getConnection()->rollback();
                                throw $e;
                            }
                    }
                else
                    return new JsonResponse(['message' => 'must be of type core_user_additional . try again '],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
    }

   
    public function setAttributes(CoreUser $user ,
    //array $attributes
    $username ,
    $usernameCanonical ,
    $email ,
    $emailCanonical ,
    $salt ,
    $locale ,
    $firstName ,
    $lastName ,
    $functionUser ,
    $phone ,
    $civility ,
    $idErp 
    )
    {
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

        /* $attributes = [
            $user->setUsername($username) , 
            $user->setUsernameCanonical($usernameCanonical) , 
            $user->setEmail($email) ,
            $user->setEmailCanonical($emailCanonical) ,
            $user->setSalt($salt) ,
            $user->setLocale($locale) ,
            $user->setFirstName($firstName) ,
            $user->setLastName($lastName) ,
            $user->setFunctionUser($functionUser) ,
            $user->setPhone($phone) ,
            $user->setCivility($civility) ,
            $user->setIdErp($idErp) 
        ]; */
    }

    public function getSimpleUserById($idUser)
    {
        $user = $this->userRepo->find($idUser);
        /* $list = $user->getCoreUserRoles();
        foreach ($list as $list)
        {
            dd($list->getCoreRole()->getName());
        } */
        //dd($user->getCoreUserRoles()->getId());
        $this->em->getConnection()->beginTransaction();
        try{
            if($user instanceof CoreUser)
                {
                     if($user->getType() == 'core_user_additional')
                        { 
                            $p = $this->serializer->serialize($user, 'json');
                            return $p ;
                        }
                    else 
                        return new JsonResponse(['message' => 'this user should be core_user_additional type .'],
                        Response::HTTP_INTERNAL_SERVER_ERROR); 
                }
            else 
                return new JsonResponse(['message' => 'this user does not exist .'], Response::HTTP_BAD_REQUEST);
        
        } catch(Exception $e){
          $em->getConnection()->rollback();
          throw $e;
            }
    }

    public function changeStatusUser($idUser,Request $request){

        $user = $this->userRepo->find($idUser);
        $donnees = json_decode($request->getContent());
        //dd($donnees->enabled == false);
        $this->em->getConnection()->beginTransaction();
        try{

            if($user instanceof CoreUser)
                {
                    if($user->getType() == 'core_user_additional')
                        {
                            if($user->isEnabled() ) // verifier si le compte de user est active
                                {
                                    if($donnees->enabled == false | $donnees->enabled == 0) // verifier si la valeur est false ou 0 pour ce cas 
                                        {
                                            $user->setEnabled($donnees->enabled); // s'il est active on le rend desactiver
                                            $this->em->flush();
                                            $this->em->getConnection()->commit();
                                            return 
                                                'user is disabled ! ' ;    
                                        }
                                    else 
                                        return new JsonResponse(['message' => 'boolean value is required or is already enabled . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);  
                                }      
                            else if (!$user->isEnabled())
                                {
                                    if($donnees->enabled == true | $donnees->enabled == 1) // verifier si la valeur est true ou 1 pour ce cas 
                                        {
                                            $user->setEnabled($donnees->enabled); // s'il est active on le rend desactiver
                                            $this->em->flush();
                                            $this->em->getConnection()->commit();
                                            return 
                                                'user is enabled ! ' ;    
                                        }
                                    else 
                                        return new JsonResponse(['message' => 'boolean value is required or is already disabled . try again'], Response::HTTP_INTERNAL_SERVER_ERROR); 
                                }   
                            else 
                                return new JsonResponse(['message' => 'error . try again'], Response::HTTP_INTERNAL_SERVER_ERROR);  
                        }

                    else 
                        return new JsonResponse(['message' => 'this user should be a core_user_additional type .'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            else 
                return new JsonResponse(['message' => 'this user does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }

    }

    }