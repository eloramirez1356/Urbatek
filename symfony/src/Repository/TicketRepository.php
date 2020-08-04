<?php

namespace App\Repository;

use App\Entity\Site;
use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function findOfSite(Site $site)
    {
        return $this->findBy(['site' => $site]);
    }


}
