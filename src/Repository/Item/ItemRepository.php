<?php

namespace App\Repository\Item;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Item\Item;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $item): void
    {
        $this->em->persist($item);
        $this->em->flush();
    }

    public function delete(Item $item): void
    {
        $this->em->remove($item);
        $this->em->flush();
    }
}
