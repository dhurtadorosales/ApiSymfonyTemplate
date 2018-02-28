<?php

namespace AppBundle\Model\Manager;


use Doctrine\ORM\EntityManager;

class UserManager
{
    private $em;

    /**
     * UserManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->em->getRepository('AppBundle:User')
            ->getActiveUsers();
    }
}