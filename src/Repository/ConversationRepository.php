<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Conversation[] Returns an array of Conversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findConversations($envoyeur)
    {
        return $this->createQueryBuilder('c')
        ->innerJoin('c.annonce', 'a') // Jointure avec la table "annonce" en utilisant l'alias "a"
        // ->andWhere('a.IsPublished = 1')
        ->andWhere(':envoyeur IN (c.Envoyeur, c.Receveur)')
        ->andWhere(':receveur IN (c.Envoyeur, c.Receveur)')
        ->setParameter('envoyeur', $envoyeur)
        ->setParameter('receveur', $envoyeur)
        ->addOrderBy('c.id', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findNumberConversations($envoyeur)
    {
        $today = new \DateTimeImmutable('today', new \DateTimeZone('Europe/Paris'));
    
        return $this->createQueryBuilder('c')
            ->where('c.Envoyeur = :envoyeur') // Filtrer par l'envoyeur de la conversation
            ->andWhere('c.createdAt >= :today') // Filtrer par la date d'aujourd'hui
            ->setParameter('envoyeur', $envoyeur) // Paramètre pour l'envoyeur
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }
    
   public function findConversationUsers($annonce, $envoyeur, $receveur): ?Conversation
   {
       return $this->createQueryBuilder('c')
       ->where('c.annonce = :annonce')
       ->andWhere(':envoyeur IN (c.Envoyeur, c.Receveur)')
       ->andWhere(':receveur IN (c.Envoyeur, c.Receveur)')
       ->setParameter('annonce', $annonce)
       ->setParameter('envoyeur', $envoyeur)
       ->setParameter('receveur', $receveur)
       ->setMaxResults(1)
       ->getQuery()
       ->getOneOrNullResult();
   }

   public function countConversationsByUser($userEmail)
   {
        $qb = $this->createQueryBuilder('c');
        $query = $qb->select('COUNT(c)')
            ->andWhere($qb->expr()->like('c.Receveur', ':email'))
            ->orWhere($qb->expr()->like('c.Envoyeur', ':email'))
            ->getQuery();

        $conversationCounts = [];

        foreach ($userEmail as $email) {
            $count = $query->setParameter('email', '%' . $email . '%')
                ->getSingleScalarResult();
            $conversationCounts[$email] = $count;
        }

        return $conversationCounts;
   }

//    public function countUnreadConversations($user)
//    {
//        return $this->createQueryBuilder('c')
//            ->select('COUNT(c.id)')
//            ->innerJoin('c.users', 'u')
//            ->andWhere('u.id = :userId')
//            ->andWhere('c.VuParReceveur = false')
//            ->andWhere('c.VuParEnvoyeur = false')
//            ->setParameter('userId', $user->getId())
//            ->getQuery()
//            ->getSingleScalarResult();
//    }
   
}
