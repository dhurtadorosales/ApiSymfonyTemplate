<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alias;
use UserBundle\Entity\User;
use AppBundle\Form\Type\AliasType;
use AppBundle\Model\Manager\AliasManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AliasController
 *
 * @Route("/{_locale}/alias", requirements={"_locale"="%app.locales%"})
 *
 * @package AppBundle\Controller
 */
class AliasController extends Controller
{
    /**
     * @Route("/all", name="alias_all")
     *
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAliasAllAction()
    {//@Security("is_granted('ROLE_ADMIN')")
        $aliasManager = $this->get(AliasManager::class);
        $alias = $aliasManager->getAliasAll();

        return $this->render('AppBundle:alias:alias.html.twig', [
            'alias' => $alias
        ]);
    }

    /**
     * @Route("/{id}", name="alias_my")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() == id")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAliasByUserAction(User $user)
    {
        $aliasManager = $this->get(AliasManager::class);
        $alias = $aliasManager->getAliasById($user);

        return $this->render('AppBundle:alias:alias.html.twig', [
            'alias' => $alias
        ]);
    }

    /**
     * @Route("/{user_id}/new", name="alias_new")
     * @Route("/edit/{user_id}/{alias_id}", name="alias_edit")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() == user_id")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "alias_id"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function formNewAliasAction(Request $request, Alias $alias = null)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();

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

        return $this->render('AppBundle:alias:form.html.twig', [
                'alias' => $alias,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{user_id}/delete/{alias_id}", name="alias_delete")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() == user_id")
     *
     * @ParamConverter("alias", class="AppBundle:Alias", options={"id" = "alias_id"})
     *
     * @param Alias $alias
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAliasAction(Alias $alias)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($alias);
            $em->flush();
            $this->addFlash(
                'success',
                'message.success'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'message.error'
            );
        }

        return $this->redirectToRoute('alias_my', [
            'id' => $this->getUser()->getId()
        ]);
    }

}
