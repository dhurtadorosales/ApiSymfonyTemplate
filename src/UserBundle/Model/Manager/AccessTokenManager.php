<?php

namespace UserBundle\Model\Manager;

use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

class AccessTokenManager
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
     * @param $token
     * @param User $user
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkToken($token, User $user)
    {
        $result = false;
        $data = $this->findAccessTokenByUser($token);

        if ($data && $data->getToken() == $token && $data->getUser() == $user) {
            $result = true;
        }

        return $result;

    }

    /**
     * @param $token
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function findAccessTokenByUser($token)
    {
        return $this->em->getRepository('UserBundle:AccessToken')
            ->findAccessTokenByToken($token);
    }
}