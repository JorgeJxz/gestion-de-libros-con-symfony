<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Libro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libro[]    findAll()
 * @method Libro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

   
    public function nPaginas($pag): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT libros FROM App\Entity\Libro libros
        WHERE libros.paginas <= :paginas');
        $query->setParameter('paginas', $pag);
        return $query->getResult();
    }

    public function buscarLibros($cadena): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT libros FROM App\Entity\Libro libros
        WHERE libros.titulo LIKE :titulo');
        $query->setParameter('titulo', '%'.$cadena.'%');
        return $query->getResult();
    }

    // /**
    //  * @return Libro[] Returns an array of Libro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Libro
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function nPaginas($pag): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT libros FROM App\Entity\Libro libros
        WHERE libros.paginas <= :paginas');
        $query->setParameter('paginas', $pag);
        return $query->getResult();
    }
    */
}
