<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function getAliasAction(User $user)
    {
        $aliasManager = $this->get('alias_manager');
        $alias = $aliasManager->getAliasById($user);

        return $this->render('alias/alias.html.twig', [
            'alias' => $alias
        ]);
    }

}
