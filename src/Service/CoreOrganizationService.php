<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\CoreOrganizationRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CoreUser; 
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoreOrganizationService 
{

    public function __construct(
        private CoreOrganizationRepository $orgRepo ,
        private SerializerInterface $serializer , 
        private EntityManagerInterface $em ,
        private PaginatorInterface $paginator 
        )
    {

    }
    
    /* public function getUserByOrg()
    {
        $org = $this->orgRepo->find($idOrg);
        if($org->getAssignedTo() == $idAdmin)
            {
                $user = 
                if()
            }
    } */

}