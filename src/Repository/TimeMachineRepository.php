<?php

namespace App\Repository;

use App\Entity\TimeMachine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Object_;

/**
 * @extends ServiceEntityRepository<TimeMachine>
 *
 * @method TimeMachine|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeMachine|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeMachine[]    findAll()
 * @method TimeMachine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeMachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeMachine::class);
    }

    public function save(TimeMachine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TimeMachine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRandomTimeMachine(): object
    {
        $TimeMachineIds = $this->createQueryBuilder('tm')->select('tm.id')->getQuery()->getSingleColumnResult();
        if (count($TimeMachineIds) == 0) {
            return new Object_();
        }

        $randomQuoteId = $TimeMachineIds[array_rand($TimeMachineIds)];

        return $this->createQueryBuilder('tm')
            ->where('tm.id = :id')
            ->setParameter('id', $randomQuoteId)
            ->getQuery()
            ->execute()[0];
    }

//    /**
//     * @return TimeMachine[] Returns an array of TimeMachine objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TimeMachine
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
