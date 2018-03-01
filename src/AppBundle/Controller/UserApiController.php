<?php

namespace AppBundle\Controller;

use AppBundle\Model\Manager\UserManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 *
 * @Route("/api/user")
 *
 * @package AppBundle\Controller
 */
class UserApiController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @FOS\Get("/all", name="api_users_all")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersApiAction()
    {
        $userManager = $this->get(UserManager::class);
        $data = $userManager->getUsers();

        /*$data = [
            [
                'email' => 'admin@admin.com',
                'pass' => 'admin',
                'name' => 'Admin',
                'lastName' => 'Admin',
                'admin' => true,
                'active' =>true
            ],
            [
                'email' => 'bruce@wayne.com',
                'pass' => 'bruce',
                'name' => 'Bruce',
                'lastName' => 'Wayne',
                'admin' => true,
                'active' =>true
            ]
        ];*/

        $view = $this->view($data, 200);
        $view->setData($data);

        return $this->handleView($view);
    }
}
