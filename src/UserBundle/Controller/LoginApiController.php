<?php

namespace UserBundle\Controller;

use AppBundle\Model\Manager\Helpers;
use AppBundle\Model\Manager\JwtAuth;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use UserBundle\Entity\User;


/**
 * Class LoginApiController
 *
 * @Route("/api")
 *
 * @package AppBundle\Controller
 */
class LoginApiController extends FOSRestController
{
    /**
     * @Rest\Post("/login", name="api_login")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function loginAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        $userName = $params->username;
        $password = $params->password;

        $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->findOneBy([
                'username' => $userName,
                //'password' => $password
            ]);

        $client = $user->getClient();

        return $this->redirectToRoute('fos_oauth_server_token', [
            'grant_type' => 'password',
            'client_id' => $client->getId() . '_' . $client->getRandomId(),
            'client_secret' => $client->getSecret(),
            'username' => $user->getUsername(),
            'password' => $password
        ]);
    }

}
