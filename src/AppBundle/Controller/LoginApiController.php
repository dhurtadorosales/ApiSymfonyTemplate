<?php

namespace AppBundle\Controller;

use AppBundle\Model\Manager\Helpers;
use AppBundle\Model\Manager\JwtAuth;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginApiController
 *
 * @Route("/api/login", requirements={"_locale"="%app.locales%"})
 *
 * @package AppBundle\Controller
 */
class LoginApiController extends FOSRestController
{
    /**
     * @Rest\Post("/", name="api_login")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);

        $json = $request->get('json', null);
        $data = [
            'status' => 'error',
            'data' => 'send.post.json'
        ];

        if ($json != null) {
            //Convert json to php object
            $params = json_encode($json);
            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            $getToken = (isset($params->getToken)) ? $params->getToken : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = 'message.email.invalid';
            $validateEmail = $this->get('validator')->validate($email, $emailConstraint);

            //Code password
            $pwd = hash('sha512', $password);

            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();

            if ($email != null && count($validateEmail) == 0 && $password != null) {
                $jwtAuth = $this->get(JwtAuth::class);

                if ($getToken == null || $getToken == false) {
                    //Code data
                    $signUp = $jwtAuth->signUp($email, $pwd);
                }
                else {
                    //Encode data
                    $signUp = $jwtAuth->signUp($email, $pwd, true);
                }

                //return $this->json($signUp);

                $data = [
                    'status' => 'status.success',
                    'data' => 'login.correct',
                    'signup' => $signUp
                ];

            } else {
                $data = [
                    'status' => 'error',
                    'data' => 'login.incorrect'
                ];
            }
        }

        return $helpers->json($data);
    }
}
