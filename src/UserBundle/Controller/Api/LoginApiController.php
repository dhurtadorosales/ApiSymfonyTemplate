<?php

namespace UserBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


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
        $json = $request->get('json', null);
        $params = json_decode($json);

        $userName = $params->username;
        $password = $params->password;

        $user = $this->getDoctrine()
            ->getRepository('UserBundle:User')
            ->findOneBy([
                'username' => $userName,
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
