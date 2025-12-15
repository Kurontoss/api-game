<?php

namespace App\Repository\Item;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Item\Food;

/**
 * @extends ServiceEntityRepository<Food>
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, Food::class);
    }

    public function save(Food $food): void
    {
        $this->em->persist($food);
        $this->em->flush();
    }

    public function delete(Food $food): void
    {
        $this->em->remove($food);
        $this->em->flush();
    }
}
