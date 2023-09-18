<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity;
use App\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GuestController extends AbstractController
{
    public function index(EntityManagerInterface $entityManager)
    {
        return $this->render('guest/index.html.twig', [
            'guests' => $entityManager->getRepository(Entity\Guest::class)->findAll(),
        ]);
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $pwdEncoder, $id)
    {
        /** @var \App\Entity\User $user */
        if ($id > 0) {
            $entity = $entityManager->find(Entity\Guest::class, $id);
        } else {
            $entity = new Entity\Guest();
        }

        $form = $this->createForm(Form\GuestEditType::class, $entity, [
            'action' => $this->generateUrl('guest_edit', [ 'id' => (int) $id ]),
            'method' => 'POST',
            'attr' => [
                'spellcheck' => 'true',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();

            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully saved');

            return $this->redirectToRoute('guest_edit', ['id' => $entity->getId()]);
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
            $entity = $entityManager->find(Entity\Guest::class, $id);

            $entityManager->remove($entity);
        }

        $entityManager->flush($entity);

        $this->addFlash('success', 'Successfully deleted');

        return $this->redirectToRoute('users');
    }
}
