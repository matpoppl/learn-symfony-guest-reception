<?php

namespace App\Controller;

use App\Auth;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function signout()
    {
        // controller can be blank: it will never be executed!
    }

    public function signin(AuthenticationUtils $authenticationUtils)
    {
        $obj = new \stdClass();
        $obj->identifier = $authenticationUtils->getLastUsername();
        $obj->password = null;
        $obj->_remember_me = false;

        return $this->render('auth/form-page.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $this->createForm(Auth\SigninType::class, $obj, [
                'action' => $this->generateUrl('signin'),
                'method' => 'POST',
            ])->createView(),
        ]);
    }

    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $entity = new User();

        $form = $this->createForm(Auth\RegisterType::class, $entity, [
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entity->setRoles(['ROLE_USER']);
            $entity->setPassword( $encoder->encodePassword($entity, $entity->getPassword()) );

            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', 'Account created');

            return $this->redirectToRoute('index');
        }

        return $this->render('auth/form-page.html.twig', [
            'error' => null,
            'form' => $form->createView(),
        ]);
    }

    public function passwordReset(Request $request, SessionInterface $session)
    {
        $form = $this->createFormBuilder()
            ->add('identifier', Type\TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])->add('submit', Type\SubmitType::class, [
                'label' => 'Reset password',
            ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $identifier = $form->get('identifier')->getData();

            $session->set('password_change_identifier', $identifier);

            $secret = 'SECRET_STRING';

            // @TODO send email

            return $this->redirectToRoute('password_change', [
                'h' => md5($secret . $identifier),
            ]);
        }

        return $this->render('auth/form-page.html.twig', [
            'error' => null,
            'form' => $form->createView(),
        ]);
    }

    public function passwordChange(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createFormBuilder(null, [
            'action' => $this->generateUrl('password_change', [
                'h' => $request->query->get('h'),
            ]),
            'method' => 'POST',
        ])
        ->add('password', Type\RepeatedType::class, [
            'type' => Type\PasswordType::class,
            'required' => true,
            'first_options' => [
                'label' => 'Enter password',
            ],
            'second_options' => [
                'label' => 'Repeat password',
            ],
        ])->add('submit', Type\SubmitType::class, [
            'label' => 'Save',
        ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $identifier = $session->get('password_change_identifier');

            $secret = 'SECRET_STRING';

            if (md5($secret . $identifier) !== $request->query->get('h')) {
                throw $this->createAccessDeniedException('Invalid token');
            }

            $user = $em->getRepository(User::class)->findOneBy([
                'username' => $identifier,
            ]);

            if (! $user) {
                throw $this->createNotFoundException('User not found');
            }

            $user->setPassword( $encoder->encodePassword($user, $form->get('password')->getData()) );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Password changed');

            return $this->redirectToRoute('index');
        }

        return $this->render('auth/form-page.html.twig', [
            'error' => null,
            'form' => $form->createView(),
        ]);
    }
}