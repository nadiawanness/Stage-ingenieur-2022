<?php

namespace App\Service;

use App\Repository\CoreOrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CoreOrganizationService
{
    public function __construct(
        private CoreOrganizationRepository $orgRepo,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator
    ) {
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
