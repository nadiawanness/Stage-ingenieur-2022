<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreUserRepository;
use App\Repository\CoreOrganizationRepository;
use App\Repository\CoreAgencyRepository;
use App\Repository\CoreCountryRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use App\Entity\CoreUserAgencies; 
use App\Entity\CoreCountry;
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
        private CoreCountryRepository $countryRepo
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
        $idCountry
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
        $user->setRoles(["ROLE_USER"]); 
        //$user->setEnabled(true);
        //$user->setHasDelegate(false);

        $country = $this->countryRepo->find($idCountry);
        
      
                if($country->isEnabled())
                    {
                        $user->setCoreCountries($country);
                    }
                else 
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'Sorry this country is disabled ! '
                        ]
                    );
    
        
        

        $organization  = $this->orgRepo->find($idOrganization);

        
                if($organization->isEnabled()) //step2 : verify if the organisation is enabled or not 
                    {
                        if($organization->getStatus() == 'valid') //step3 : verify if the organisation status (valid or not) 
                            {
                                $user->addOrganization($organization);
                            }
                        else 
                            return new JsonResponse(
                                [
                                    'error' => '400' ,
                                    'message' => 'Sorry cannot add this organization to this user, it is not a valid organization ! '
                                ]
                            );
                    }
                else 
                    return new JsonResponse(
                        [
                            'error' => '400' ,
                            'message' => 'Sorry cannot add this organization to this user, it is disabled ! '
                        ]
                    );

           
        
        
        $agency = $this->agencyRepo->find($idAgency);
        
            if($agency->getCoreOrganization()->getId() == $organization->getId()) //verify if the organization exist in the organization
                {
                    //dd($agency->isEnabled());
                    if($agency->isEnabled()) //verify if the agency is enabled or not
                        {
                            //dd($agency->IsEnbled());
                            $coreUserAgency = new CoreUserAgencies();
                            $coreUserAgency->setCoreUser($user);    
                            $coreUserAgency->setCoreAgency($agency);
                            $this->em->persist($coreUserAgency);
                        }
                    else 
                        return new JsonResponse(
                            [
                                'error' => '400' ,
                                'message' => 'Sorry cannot add this agency  to this user, it is disabled ! '
                            ]
                        );

                
                }
            else 
                return new JsonResponse(
                    [
                        'error' => '400' ,
                        'message' => 'Sorry cannot add this agency  to this user, it is not in this organization ! '
                    ]
                );
    
        

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

    /* public function addCoreUserAgencies(
        $idUSer , 
        $idAgency 
    )
    {
       $jsonRecu = $request->getContent();
       $this->em->getConnection()->beginTransaction();
       try{
        $userAgencies = $this->serializer->deserialize($jsonRecu, CoreUserAgencies::class,'json');
        $user = $this->orgRepo->find($idUser);
        $agency = $this->agencyRepo->find($idAgency);
        
        $userAgencies->setCoreUser($user);
        $userAgencies->setCoreAgency($agency); 

        $this->em->persist($userAgencies);
        $this->em->flush();
        $this->em->getConnection()->commit();
        //return $userAgencies;

        } catch(Exception $e){
            $em->getConnection()->rollback();
            throw $e;
        }

    } */

    public function getSimpleUserById($idUser)
    {
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
       try{
        if($user->getType() == 'core_user_additional')
            {
                $p = $this->serializer->serialize($user, 'json');
                return $p ;
            }
        else 
            return new JsonResponse(
                [
                    'error' => '400' ,
                    'message' => 'Should be a core user additional'
                ]
            );

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
                                    return new JsonResponse(
                                        ['message' => "error. please try again."],
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