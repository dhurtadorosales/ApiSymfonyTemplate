<?php

namespace AppBundle\Model\Manager;

use Firebase\JWT\JWT;

class JwtAuth
{
    public $em;
    public $key;

    /**
     * JwtAuth constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param $email
     * @param $password
     * @param null $getToken
     * @return array
     */
    public function signUp($email, $password, $getToken = null)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy([
            'email' => $email,
            'pass' => $password
        ]);

        $signUp = false;
        if (is_object($user)) {
            $signUp = true;
        }

        $data = [
            'status' => 'error',
            'data' => 'login.failed'
        ];

        if ($signUp == true) {
            $token = [
                'sub' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'surname' => $user->getLastname(),
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($token, $this->key, 'sha512');
            $decoded = JWT::decode($jwt, $this->key, ['sha512']);

            if ($getToken == null) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        }

        return $data;
    }

    /**
     * @param $jwt
     * @param bool $getIdentity
     * @return bool
     */
    public function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        $result = false;
        $decoded = false;
        try {
            $decoded = JWT::decode($jwt, $this->key, ['sha512']);
        }
        catch (\UnexpectedValueException $e) {
            $auth = false;
        }
        catch (\DomainException $e) {
            $auth = false;
        }
        if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        }
        else {
            $auth = false;
        }
        $result = $decoded;
        if ($getIdentity == false) {
            $result = $auth;
        }
        return $result;
    }

}