<?php

namespace AppBundle\Model\Manager;


use AppBundle\Entity\User;
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
    public function getAliasAll()
    {
        return $this->em->getRepository('AppBundle:Alias')
            ->findAll();
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function getAliasById(User $user)
    {
        return $this->em->getRepository('AppBundle:Alias')
            ->getAliasByUser($user);
    }
}