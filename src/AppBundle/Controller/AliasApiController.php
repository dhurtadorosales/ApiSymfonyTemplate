<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alias;
use AppBundle\Model\Manager\AliasManager;
use AppBundle\Model\Manager\Helpers;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $aliasManager = $this->get(AliasManager::class);
        $data = $aliasManager->getAliasAll();

        $helpers = $this->get(Helpers::class);
        $alias = $helpers->json($data);

        return $alias;
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
