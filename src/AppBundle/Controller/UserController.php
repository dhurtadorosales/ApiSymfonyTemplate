<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\ProfileType;
use AppBundle\Form\Type\UserType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route("/{_locale}", requirements={"_locale"="%app.locales%"})
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/users/all", name="users_all")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction()
    {
        $userManager = $this->get('user_manager');
        $users = $userManager->getUsers();

        return $this->render('user/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/new", name="user_new")
     * @Route("/user/edit/{id}", name="user_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formUserAction(Request $request, User $user = null)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();

        if (null == $user) {
            $user = new User();
            $em->persist($user);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $encoder = $this->container->get(UserPasswordEncoderInterface::class);

                $pass = $encoder->encodePassword($user, $form['password']->getData());
                $user->setPass($pass);
                
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

        return $this->render('user/form.html.twig', [
            'user' => $user,
            'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction(User $user)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($user);
            $em->flush();
            $this->addFlash(
                'success',
                'User deleted'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Error'
            );
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/login", name="login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('user/login.html.twig', [
            'last_user' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/check", name="check")
     * @Route("/logout", name="logout")
     */
    public function checkAction()
    {

    }

    /**
     * @Route("/profile", name="profile")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $passForm = $form->get('new')->get('first')->getData();

            if ($passForm) {
                $encoder = $this->container->get(UserPasswordEncoderInterface::class);

                $pass = $encoder->encodePassword($user, $form->get('password')->getData());

                $user->setPass($pass);

                $user->setClave($pass);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('user/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
