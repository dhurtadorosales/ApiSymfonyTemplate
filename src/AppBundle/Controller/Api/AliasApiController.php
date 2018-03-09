<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Alias;
use AppBundle\Model\Manager\AliasManager;
use AppBundle\Model\Manager\Helpers;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Model\Manager\AccessTokenManager;

/**
 * Class AliasApiController
 *
 * @Route("/api/alias", requirements={"_locale"="%app.locales%"})
 *
 * @package AppBundle\Controller
 */
class AliasApiController extends FOSRestController
{
    /**
     * @Rest\Get("/all", name="api_alias_all", options={"method_prefix"=false})
     *
     * @return array
     */
    public function getAliasAllAction()
    {
        //FALTA AUTORIZACIÓN


        $aliasManager = $this->get(AliasManager::class);
        $data = $aliasManager->findAliasAll();

        $helpers = $this->get(Helpers::class);
        $alias = $helpers->json($data);

        return $alias;
    }

    /**
     * @Rest\Post("/{id}", name="api_alias_my")
     *
     * @param Request $request
     * @param User $user
     *
     * @return array
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAliasByUserAction(Request $request, User $user)
    {
        $authorization = $request->get('authorization', null);

        $accessTokenManager = $this->get(AccessTokenManager::class);
        $checkToken = $accessTokenManager->checkToken($authorization, $user);

        if ($checkToken) {
            $aliasManager = $this->get(AliasManager::class);
            $alias = $aliasManager->findAliasByUser($user);

            $helpers = $this->get(Helpers::class);
            $data = $helpers->json($alias);

        } else {
            $data = [
                'status' => 'error',
                'code' => 400,
                'data' => 'message.error.not_auth'
            ];
        }

        return $data;
    }

    /**
     * @Rest\Post("/new", name="api_alias_new")
     * @Rest\Post("/edit/{id}", name="api_alias_edit")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "id"})
     *
     * @param Request $request
     * @param Alias|null $alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function formAliasAction(Request $request, Alias $alias = null)
    {

        //FALTA AUTORIZACIÓN

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if (null == $alias) {
            $alias = new Alias();
        }

        $alias
            ->setName($params->name)
            ->setOrigin($params->origin);

        $em->persist($alias);
        $em->flush();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $alias
        ];

        return $helpers->json($data);

    }

    /**
     * @Rest\Post("/delete/{id}", name="api_alias_delete")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "id"})
     *
     * @param Alias $alias
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteAliasAction(Alias $alias)
    {

        //FALTA AUTORIZACIÓN


        $helpers = $this->get(Helpers::class);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($alias);
        $em->flush();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $alias
        ];

        return $helpers->json($data);
    }

}
