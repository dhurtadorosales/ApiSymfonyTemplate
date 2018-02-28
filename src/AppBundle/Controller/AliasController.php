<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alias;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AliasType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AliasController
 * @Route("/{_locale}", requirements={"_locale"="%app.locales%"})
 * @package AppBundle\Controller
 */
class AliasController extends Controller
{
    /**
     * @Route("/alias/all", name="alias_all")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAliasAllAction()
    {
        $aliasManager = $this->get('alias_manager');
        $alias = $aliasManager->getAliasAll();

        return $this->render('alias/alias.html.twig', [
            'alias' => $alias
        ]);
    }

    /**
     * @Route("/alias/{id}", name="alias_my")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAliasByUserAction(User $user)
    {
        $aliasManager = $this->get('alias_manager');
        $alias = $aliasManager->getAliasById($user);

        return $this->render('alias/alias.html.twig', [
            'alias' => $alias
        ]);
    }

    /**
     * @Route("/alias/{user_id}/new", name="alias_new")
     * @Route("/alias/edit/{user_id}/{alias_id}", name="alias_edit")
     * @param Request $request
     * @param Alias $alias
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formAliasAction(Request $request, Alias $alias = null)
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
                    'Success'
                );

                return $this->redirectToRoute('homepage');
            }
            catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Error'
                );
            }
        }

        return $this->render('alias/form.html.twig', [
                'alias' => $alias,
                'form' => $form->createView()
            ]
        );
    }

}
