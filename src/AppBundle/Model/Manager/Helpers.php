<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 1/03/18
 * Time: 18:37
 */

namespace AppBundle\Model\Manager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class Helpers
 * @package AppBundle\Model\Manager
 */
class Helpers
{
    public $em;

    /**
     * Helpers constructor.
     * @param $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param $data
     * @return Response
     */
    public function json($data)
    {
        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($data) {
            return $data->getId();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $json = $serializer->serialize($data, 'json');

        $response = new Response();
        $response
            ->setContent($json)
            ->headers->set('Content-Type', 'application/json');

        return $response;
    }
}