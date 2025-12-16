<?php

namespace App\Repository\Item;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Item\ItemInstance;

/**
 * @extends ServiceEntityRepository<ItemInstance>
 */
class ItemInstanceRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, ItemInstance::class);
    }

    public function save(ItemInstance $itemInstance): void
    {
        $this->em->persist($itemInstance);
        $this->em->flush();
    }

    public function delete(ItemInstance $itemInstance): void
    {
        $this->em->remove($itemInstance);
        $this->em->flush();
    }
}
