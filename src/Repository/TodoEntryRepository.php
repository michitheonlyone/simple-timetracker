<?php

namespace App\Repository;

use App\Entity\TodoEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TodoEntry>
 *
 * @method TodoEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoEntry[]    findAll()
 * @method TodoEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoEntry::class);
    }

    public function save(TodoEntry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TodoEntry $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
