<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 * @Route("/")
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/users/all", name="users_all")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction()
    {
        $userManager = $this->get('user_manager');
        $users = $userManager->getUsers();

        return $this->render('@AppBundle/Resources/views/users.html.twig', [
            'users' => $users
        ]);
    }
}
