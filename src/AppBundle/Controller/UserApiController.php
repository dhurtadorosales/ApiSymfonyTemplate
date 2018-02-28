<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 *
 * @Route("/api/user")
 *
 * @package AppBundle\Controller
 */
class UserApiController extends Controller
{
    /**
     * @Route("/all", name="api_users_all")
     *
     * @return array
     */
    public function getUsersApiAction()
    {
        $userManager = $this->get('user_manager');
        $users = $userManager->getUsers();

        return [
            'users' => $users
        ];
    }
}
