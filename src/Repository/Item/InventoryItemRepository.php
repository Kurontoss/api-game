<?php

namespace App\Repository\Item;

use App\Entity\Item\InventoryItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<InventoryItem>
 */
class InventoryItemRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $em
    ) {
        parent::__construct($registry, InventoryItem::class);
    }

    public function save(InventoryItem $inventoryItem): void
    {
        $this->em->persist($inventoryItem);
        $this->em->flush();
    }

    public function delete(InventoryItem $inventoryItem): void
    {
        $this->em->remove($inventoryItem);
        $this->em->flush();
    }
}
