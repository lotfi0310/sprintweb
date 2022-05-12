<?php


namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvennementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Evenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function triCroissant(){
        return $this->createQueryBuilder('evenement')
            ->orderBy('evenement.capacite ','ASC')
            ->getQuery()->getResult();
    }

    public function triDecroissant(){
        return $this->createQueryBuilder('evenement')
            ->orderBy('evenement.capacite ','DESC')
            ->getQuery()->getResult();
    }
    public function findAllEvents(){
        return $this->createQueryBuilder('evenement')
            ->orderBy('evenement.capacite ','DESC')
        ;
    }
    function SearchByEmp($nsc)
    {
        return $this->createQueryBuilder('evenement')
            ->where ('evenement.nom = :nom')
            ->setParameter('nom',$nsc)
            ->getQuery()->getResult();


    }
    function SearchNom($nsc)

    {
        return $this->createQueryBuilder('q')
            ->where ('q.nom LIKE :nom_evenement')
            ->setParameter('nom_evenement','%'.$nsc.'%')
            ->getQuery()->getResult();



    }
    public function findEntitiesByString($str)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\evenement p
            WHERE p.nom LIKE :str'

        )->setParameter('str', $str);

        // returns an array of Product objects
        return $query->getResult();
    }

    function reche($data)
    {
        return $this->createQueryBuilder('evennement')
            ->Where('evennement.nom Like :nom')
            ->setParameter('nom', '%'.$data.'%')
            ->getQuery()->getResult();
    }
}