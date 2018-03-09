<?php

namespace UserBundle\Controller;

use UserBundle\Entity\User;
use UserBundle\Form\Type\PasswordChangeType;
use UserBundle\Form\Type\UserType;
use AppBundle\Model\Manager\MailerManager;
use UserBundle\Model\Manager\UserManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 *
 * @Route("/user")
 *
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/all", name="users_all")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction()
    {
        $userManager = $this->get(UserManager::class);
        $users = $userManager->findActiveUsers();

        return $this->render('UserBundle::users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/new", name="user_new")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function formNewUserAction(Request $request)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();

        $user = new User();
        $em->persist($user);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user
                    ->setEnabled(true);

                $encoder = $this->get('security.password_encoder');
                $pass = $encoder->encodePassword($user, $form->get('pass')->getData());

                $user->setPass($pass);
                
                $em->flush();

                $mailerManager = $this->get(MailerManager::class);
                $mailerManager->sendWelcomeToUser($user);

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

        return $this->render('UserBundle::form.html.twig', [
            'user' => $user,
            'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction(User $user)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        try {
            if (!$user->hasRole(['ROLE_ADMIN'])) {
                $user
                    ->setEnabled(false);
                $em->flush();

                $this->addFlash(
                    'success',
                    'message.success'
                );
            } else {
                $this->addFlash(
                    'error',
                    'message.admin.error'
                );
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'message.error'
            );
        }
        return $this->redirectToRoute('users_all');
    }

    /**
     * @Route("/{id}/profile", name="profile")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() == id")
     *
     * @ParamConverter("user", class="UserBundle:User", options={"id" = "id"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileViewAction()
    {
        $user = $this->getUser();

        return $this->render('UserBundle::profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/change-password", name="change_password")
     *
     * @Security("is_granted('ROLE_USER') and user.getId() == id")
     *
     * @ParamConverter("user", class="UserBundle:User", options={"id" = "id"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function changePasswordAction(Request $request)
    {
        /** @var EntityManager $em */
        $em =$this->getDoctrine()->getManager();

        $user = $this->getUser();
        $em->persist($user);

        $form = $this->createForm(PasswordChangeType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            try {
                $passForm = $form->get('new')->get('first')->getData();

                if ($passForm) {
                    $encoder = $this->get('security.password_encoder');

                    $pass = $encoder->encodePassword($user, $passForm);
                    $user->setPassword($pass);
                }

                $em->flush();

                $this->addFlash(
                    'success',
                    'message.success'
                );

                return $this->redirectToRoute('homepage');

            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'message.error'
                );
            }
        }

        return $this->render('UserBundle::change_pass.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
