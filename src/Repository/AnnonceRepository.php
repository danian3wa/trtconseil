<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Annonce;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
  /**
   * @var PaginatorInterface
   */

  private $paginator;

  public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
  {
    parent::__construct($registry, Annonce::class);
    $this->paginator = $paginator;
  }

  private function getSearchQuery(SearchData $search): QueryBuilder
  {
    $query = $this
      ->createQueryBuilder('p')
      ->select('c', 'p')
      ->join('p.poste', 'c')
      ->orderBy('p.id', 'DESC');

    if (!empty($search->q)) {
      $query = $query
        ->andWhere('p.titre LIKE :q')
        ->setParameter('q', "%{$search->q}%");
    }

    if (!empty($search->poste)) {
      $query = $query
        ->andWhere('c.id IN (:poste)')
        ->setParameter('poste', $search->poste);
    }

    return $query;
  }

  /**
   * Récupère les annonces en lien avec une recherce
   * @return PaginationInterface
   */

  public function findSearch(SearchData $search): PaginationInterface
  {
    $query = $this->getSearchQuery($search)->getQuery();
    return $this->paginator->paginate(
      $query,
      $search->page,
      6
    );
  }

   /**
     * Finds annonces by poste.
     *
     * @param string $poste The label of the poste to search for
     * @return array An array of annonces
     */
    public function findByPoste(string $poste): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.poste', 'p')
            ->andWhere('p.libelle = :poste')
            ->setParameter('poste', $poste)
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds annonces by poste ID.
     *
     * @param int $postId The ID of the poste to search for
     * @return array An array of annonces
     */
    public function findByPosteId(int $postId): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.poste', 'p')
            ->andWhere('p.id = :postId')
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getResult();
    }

  //    /**
  //     * @return Annonce[] Returns an array of Annonce objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('a')
  //            ->andWhere('a.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('a.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Annonce
  //    {
  //        return $this->createQueryBuilder('a')
  //            ->andWhere('a.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
