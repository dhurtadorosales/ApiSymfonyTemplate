<?php

namespace AppBundle\Model\Manager;


use UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class AliasManager
{
    private $em;

    /**
     * AliasManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function findAliasAll()
    {
        return $this->em->getRepository('AppBundle:Alias')
            ->findAliasAll();
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function findAliasByUser(User $user)
    {
        return $this->em->getRepository('AppBundle:Alias')
            ->findAliasByUser($user);
    }
}