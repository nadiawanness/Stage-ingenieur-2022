<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreUserRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CoreAdminAdditionalService 
{
    public function __construct(
        private CoreUserRepository $userRepo ,
        private SerializerInterface $serializer , 
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator
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
        $p = $this->serializer->serialize($pagination, 'json');
        return $p;
        
    }

    public function addAdmin(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordHasher
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
        $user->setType('core_admin_additional');
        $user->setEnabled(true);
        $user->setHasDelegate(false);
        $user->setRoles(["ROLE_ADMIN"]); 

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