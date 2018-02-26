<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 * @Route("/api")
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/users/all", name="users_all")
     * @return array
     */
    public function getUserAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return [
            'users' => $users
        ];
    }
}
