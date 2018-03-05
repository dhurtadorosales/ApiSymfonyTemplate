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

     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAliasAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $alias = new Alias();
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

    /*
     * @Route("/{id}", name="api_alias_my")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
    public function getAliasByUserAction(User $user)
    {
        $aliasManager = $this->get('alias_manager');
        $alias = $aliasManager->getAliasById($user);

        return $this->render('alias/alias.html.twig', [
            'alias' => $alias
        ]);
    }

    /*
     * @Route("/{user_id}/new", name="api_alias_new")
     * @Route("/edit/{user_id}/{alias_id}", name="alias_edit")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "alias_id"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
    public function formNewAliasAction(Request $request, Alias $alias = null)
    {
         @var EntityManager $em
        $em = $this->getDoctrine()->getManager();

        if (null == $alias) {
            $alias = new Alias();
            $em->persist($alias);
        }

        $form = $this->createForm(AliasType::class, $alias);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $alias
                    ->setUser($this->getUser());
                $em->flush();

                $this->addFlash(
                    'success',
                    'message.success'
                );

                return $this->redirectToRoute('alias_my', [
                    'id' => $this->getUser()->getId()
                ]);
            }
            catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'message.error'
                );
            }
        }

        return $this->render('alias/form.html.twig', [
                'alias' => $alias,
                'form' => $form->createView()
            ]
        );
    }

    /*
     * @Route("/{user_id}/delete/{alias_id}", name="api_alias_delete")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "alias_id"})
     *
     * @param Alias $alias
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
    public function deleteAliasAction(Alias $alias)
    {
         @var EntityManager $em
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($alias);
            $em->flush();
            $this->addFlash(
                'success',
                'message.success'
            );/*
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'message.error'
            );
        }

        return $this->redirectToRoute('alias_my', [
            'id' => $this->getUser()->getId()
        ]);
    }*/

}
