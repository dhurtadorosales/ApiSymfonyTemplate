<?php

namespace UserBundle\Controller\Api;

use AppBundle\Model\Manager\Helpers;
use UserBundle\Model\Manager\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 *
 * @Route("/api/user")
 *
 * @package AppBundle\Controller
 */
class UserApiController extends FOSRestController
{
    /**
     * @Rest\Get("/all", name="api_users_all", options={"method_prefix"=false})
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
