<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
/**
 * AliasRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AliasRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function getAliasByUser(User $user)
    {
        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $query = $em->createQueryBuilder()
            ->select('a')
            ->addSelect('u')
            ->from('AppBundle:Alias', 'a')
            ->join('a.user', 'u')
            ->where('u = :user')
            ->andWhere('u.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();

        return $query;
    }
}
