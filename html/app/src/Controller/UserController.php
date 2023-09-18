<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\UserEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager)
    {
        return $this->render('user/index.html.twig', [
            'users' => $entityManager->getRepository(User::class)->findAll(),
        ]);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $pwdEncoder, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /** @var \App\Entity\User $user */
        if ($id > 0) {
            $user = $entityManager->find(User::class, $id);
        } else {
            $user = new User();
        }

        $obj = new \stdClass();
        $obj->username = $user->getUsername();
        $obj->role = current($user->getRoles());
        $obj->password = null;

        $form = $this->createForm(UserEditType::class, $obj, [
            'action' => $this->generateUrl('user_edit', [ 'id' => (int) $id ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $obj = $form->getData();

            $user->setUsername($obj->username);
            $user->setRoles([$obj->role]);

            if ($obj->password) {
                $user->setPassword($pwdEncoder->encodePassword($user, $obj->password));
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully saved');

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('page-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, EntityManagerInterface $entityManager)
    {
        $csrf = $request->request->get('csrf');

        if (! $request->isMethod('POST') || ! $this->isCsrfTokenValid('delete', $csrf)) {
            throw $this->createAccessDeniedException('Invalid request');
        }

        foreach ( $request->request->get('id') as $id) {
            $entity = $entityManager->find(User::class, $id);

            $entityManager->remove($entity);
        }

        $entityManager->flush($entity);

        $this->addFlash('success', 'Successfully deleted');

        return $this->redirectToRoute('users');
    }
}
