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
        $p = $this->serializer->serialize($pagination, 'json');
        return $p;
        
    }

    /* public function getByOrganization($value)
    {
        /* $org = $this->orgRepo->findAll();
        dd($org = $this->orgRepo->findAll()); 
        $org = $this->orgRepo->findByOrg($value);
        $p = $this->serializer->serialize($org , 'json',['groups' => 'coreorganization:read']);
        //dd($this->orgRepo->findAll());
        return $p;
    } */

    public function getByOrganization($admin)
    {
        $user = $this->userRepo->findByOrg($admin);
        $p = $this->serializer->serialize($user , 'json',['groups' => 'coreorganization:read']);
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
            return new JsonResponse(['message' => 'this country does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);
      

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
                return new JsonResponse(['message' => 'this organization does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
                
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
            return new JsonResponse(['message' => 'this agency does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
        
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
            return new JsonResponse(['message' => 'this role does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);

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
                return new JsonResponse(['message' => 'this user does not exist .'], Response::HTTP_INTERNAL_SERVER_ERROR);
        
        } catch(Exception $e){
          $em->getConnection()->rollback();
          throw $e;
            }
    }

    public function disableUser($idUser, $value)
    {
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
            if($user->getType() == 'core_user_additional')
            {
                if($user->isEnabled() && ($value == 0 | $value == false) )
                {
                    /* if($value == 0 | $value == false)
                    { */
                        $user->setEnabled($value);
                        $this->em->flush();
                        $this->em->getConnection()->commit();
                        return $user;
                    /*}
                    else 
                    {
                        return 'A boolean value should be given to this field ! ' ;
                    }*/
                }
                else 
                
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'User is already disabled or a boolean should be given ! Verify please !'
                        ]
                    );
                
            }

            else 
        
                return new JsonResponse(
                    [
                        'error' => '400' ,
                        'message' => 'The user should be with a core_user_additional type !'
                    ]
                );

        }catch(Exception $e)
        {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

    /* public function userExist(
        $idUser
    )
    {
        $user = $this->userRepo->find($idUser);
        $coreUser = new CoreUser();
        dd($coreUser->getMyCollectionValues()->contains($user));
    } */

    public function enableUser($idUser,$value)
    {
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
            if($user->getType() == 'core_user_additional')
                {   
                    if(!($user->IsEnabled()) && ($value == 1 | $value == true) )
                        {
                            /* if($value == 1 | $value == true)
                                { */
                                    $user->setEnabled($value);
                                    $this->em->flush();
                                    $this->em->getConnection()->commit();
                                    return $user;
                               /*  }
                            else  
                                {
                                    return 'A boolean value should be given to this field ! ' ;
                                } */
                        }

                    else 
                        
                            return new JsonResponse(
                                [
                                    'error' => '400' ,
                                    'message' => 'User is already enabled or a boolean should be given ! '
                                ]
                            );
                        
                }
            else 
             
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'The user should be with a core_user_additional type ! '
                        ]
                    );
                

        }catch(Exception $e)
        {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

    public function changeStatusUser($idUser){

        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
            if($user instanceof CoreUser)
                {
                    if($user->getType() == 'core_user_additional')
                        {
                            
                        
                            if($user->isEnabled() ) //verifier si le compte de user est active
                                {
                                    

                                    $user->setEnabled(false); // s'il est active on le rend desactiver
                                    $this->em->flush();
                                    $this->em->getConnection()->commit();
                                    return 
                                        'user is disabled ! ' ;      
                                
                                    
                                }      
                            else if (!$user->isEnabled())
                                {
                                
                                    $user->setEnabled(true); // s'il est active on le rend desactiver
                                    $this->em->flush();
                                    $this->em->getConnection()->commit();
                                    return 
                                        'user is enabled ! ' ;
                                    
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

    /* public function changeStatusUser($idUser,bool $value){

        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
                 if($user->getType() == 'core_user_additional')
                    {
                        
                        if(is_bool($value) )
                            {
                                if($user->isEnabled() && $value == false) //verifier si le compte de user est active
                                    {
                                        

                                            $user->setEnabled($value); // s'il est active on le rend desactiver
                                            $this->em->flush();
                                            $this->em->getConnection()->commit();
                                            return 
                                                'user is disabled ! ' ;      
                                      
                                         
                                    }      
                                else if (!$user->isEnabled() && $value)
                                    {
                                      
                                            $user->setEnabled(true); // s'il est active on le rend desactiver
                                            $this->em->flush();
                                            $this->em->getConnection()->commit();
                                            return 
                                                'user is enabled ! ' ;
                                        
                                    }   
                            }

                        else 
                            return new JsonResponse(
                                ['message' => "boolean value is required. please try again."],
                                     Response::HTTP_INTERNAL_SERVER_ERROR);
                               
                            
                    }
                    

                else 
                    return new JsonResponse(
                        ['message' => "user must be of type core_user_additional. please try again."],
                             Response::HTTP_INTERNAL_SERVER_ERROR);
            


        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }
    } */

    /* public function changeStatusUser($idUser,$value){

        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
                 if($user->getType() == 'core_user_additional')
                    {
                        
                               
                                if($user->isEnabled() && $value == false) //verifier si le compte de user est active
                                    {
                                        
                                                $user->setEnabled($value); // s'il est active on le rend desactiver
                                                $this->em->flush();
                                                $this->em->getConnection()->commit();
                                         
                                    }      
                                else 
                                       
                                    return new JsonResponse(
                                        [
                                            'error' => '400' ,
                                            'message' => 'User is already disabled  ! ' 
                                        ]
                                    );
                                            
                                        
                            
                                    }
                    

                else 
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'This is not a core_user_additional ! ' 
                        ]
                    );


        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }
    } */


    public function changeStatusUser2($idUser,bool $value){
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{
                 if($user->getType() == 'core_user_additional')
                    {
                        //dd($user->isEnabled());
                               
                                if(!$user->isEnabled() ) //verifier si le compte de user est active
                                    {
                                        
                                                $user->setEnabled(true); // s'il est active on le rend desactiver
                                                $this->em->flush();
                                                $this->em->getConnection()->commit();
                                         
                                    }      
                                else 
                                       
                                    return new JsonResponse(
                                        [
                                            'error' => '400' ,
                                            'message' => 'User is already enabled  ! ' 
                                        ]
                                    );
                                            
                                        
                            
                                    }
                    

                else 
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'This is not a core_user_additional ! ' 
                        ]
                    );


        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }
    }


    /* public function enableUser($idUser){ // changer le nom : changeStatus
        
       $disabledUser = $this->userRepo->find($idUser);
       $this->em->getConnection()->beginTransaction();
       try{
        if(!$disabledUser->IsEnabled()  ){
            $disabledUser->setEnabled(true);
            $this->em->flush();
            $this->em->getConnection()->commit();
            return $disabledUser;
        }

        else {
            return 'user account is already enabled ' ;
        } 

    } catch(Exception $e){
        $em->getConnection()->rollback();
        throw $e;
    }
        
    }

    public function DisableUser($idUser){
        
        $disabledUser = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
       try{
         if($disabledUser->IsEnabled()){
             $disabledUser->setEnabled(false);
             $this->em->flush();
             $this->em->getConnection()->commit();
             return $disabledUser;
         }
 
         else {
             return 'user account is already disabled ' ;
         } 

        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }
         
     } */

    }