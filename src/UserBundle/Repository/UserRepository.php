<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getActiveUsers()
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('u')
            ->from('UserBundle:User', 'u')
            ->where('u.active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return $query;
    }
}