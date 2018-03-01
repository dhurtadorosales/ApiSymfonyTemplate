<?php

namespace AppBundle\Controller;

use AppBundle\Model\Manager\Helpers;
use AppBundle\Model\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersApiAction()
    {
        $userManager = $this->get(UserManager::class);
        $data = $userManager->getUsers();

        $helpers = $this->get(Helpers::class);
        $users = $helpers->json($data);

        return $users;
    }
}
