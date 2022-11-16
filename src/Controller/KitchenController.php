<?php

namespace App\Controller;

use App\Entity\Kitchen;
use App\Form\KitchenType;
use App\Repository\KitchenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kitchen')]
class KitchenController extends AbstractController
{
    #[Route('/', name: 'app_kitchen_index', methods: ['GET'])]
    public function index(KitchenRepository $kitchenRepository): Response
    {
        return $this->render('kitchen/index.html.twig', [
            'kitchens' => $kitchenRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_kitchen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KitchenRepository $kitchenRepository): Response
    {
        $kitchen = new Kitchen();
        $form = $this->createForm(KitchenType::class, $kitchen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kitchenRepository->add($kitchen, true);

            return $this->redirectToRoute('app_kitchen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kitchen/new.html.twig', [
            'kitchen' => $kitchen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kitchen_show', methods: ['GET'])]
    public function show(Kitchen $kitchen): Response
    {
        return $this->render('kitchen/show.html.twig', [
            'kitchen' => $kitchen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_kitchen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kitchen $kitchen, KitchenRepository $kitchenRepository): Response
    {
        $form = $this->createForm(KitchenType::class, $kitchen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $kitchenRepository->add($kitchen, true);

            return $this->redirectToRoute('app_kitchen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kitchen/edit.html.twig', [
            'kitchen' => $kitchen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kitchen_delete', methods: ['POST'])]
    public function delete(Request $request, Kitchen $kitchen, KitchenRepository $kitchenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kitchen->getId(), $request->request->get('_token'))) {
            $kitchenRepository->remove($kitchen, true);
        }

        return $this->redirectToRoute('app_kitchen_index', [], Response::HTTP_SEE_OTHER);
    }
}
