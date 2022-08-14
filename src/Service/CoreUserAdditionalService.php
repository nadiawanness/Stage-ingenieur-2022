<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreUserRepository;
use App\Repository\CoreOrganizationRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CoreUserAdditionalService 
{
    public function __construct(
        private CoreUserRepository $userRepo ,
        private SerializerInterface $serializer , 
        private EntityManagerInterface $em ,
        private PaginatorInterface $paginator ,
        private CoreOrganizationRepository $orgRepo ,
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
    
  

    public function addSimpleUser(
        Request $request ,
        ValidatorInterface $validator ,
        UserPasswordHasherInterface $userPasswordHasher ,
        $idOrganization
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
        $user->setEnabled(true);
        $user->setHasDelegate(false);

        $organization  = $this->orgRepo->find($idOrganization);

        //step1 : verify the existence of the organisation 
        //step2 : verify if the organisation is enabled or not 
        //step3 : verify if the organisation status (valid or not) 

        if($organization->IsEnabled())
        {
            $user->addOrganization($organization);
        }
        else 
        {
            return 'Sorry cannot add this organization , it is disabled ! ' ;
        }
        

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
        $this->em->getConnection()->beginTransaction();
       try{
        if($user->getType() == 'core_user_additional')
            {
                $p = $this->serializer->serialize($user, 'json');
                return $p ;
            }
        else 
            return 'Sorry the user need to be of a type core_user_additional';

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
                if($user->IsEnabled() && ($value == 0 | $value == false) )
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
                {
                    return 'User is already disabled or a boolean should be given ! Verify please ! ' ;
                }
            }

            else 
            {
                return 'The user should be with a core_user_additional type ! ' ;
            }

        }catch(Exception $e)
        {
            $em->getConnection()->rollback();
            throw $e;
        }
    }

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
                        {
                            return 'User is already enabled or a boolean should be given ! ' ;
                        } 
                }
            else 
                {
                    return 'The user should be with a core_user_additional type ! ' ;
                }

        }catch(Exception $e)
        {
            $em->getConnection()->rollback();
            throw $e;
        }
    }


    public function changeStatusUser($idUser,bool $value){
        $user = $this->userRepo->find($idUser);
        $this->em->getConnection()->beginTransaction();
        try{


                if($user->getType() == 'core_user_additional')
                    {
                       
                                if($user->IsEnabled()) //verifier si le compte de user est active
                                    {
                                        if(!$value)
                                            {
                                                $user->setEnabled(!$value); // s'il est active on le rend desactiver
                                                $this->em->flush();
                                                $this->em->getConnection()->commit();
                                                return $user;
                                            }
                                        else if($value)
                                            {
                                                return 'User is already enabled ! ' ;
                                            }
                                        else 
                                            {
                                                return 'Boolean value is required to this field ! ' ;
                                            }
                                        
                            
                                    }
                                else if(!$user->IsEnabled()) // verifier si le compte de user est desactive
                                    {
                                        if($value)
                                            {
                                                $user->setEnabled($value); // s'il est desactive on le rend active
                                                $this->em->flush();
                                                $this->em->getConnection()->commit();
                                                return $user;
                                            }
                                        else if(!$value)
                                            {
                                                return 'User is already disabled ! ' ;
                                            }
                                        else 
                                            {
                                                return 'Boolean value is required to this field ! ' ;
                                            }
                                        
                                        
                                    } 
                        
                    }

                else 
                    return 'This is not a core_user_additional ! ' ;


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