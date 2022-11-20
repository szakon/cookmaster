<?php

namespace App\Controller;

use App\Entity\Kitchen;
use App\Entity\Cookbook;
use App\Form\KitchenType;
use App\Entity\Member;
use App\Repository\KitchenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/kitchen')]
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

    #[Route('/new/{id}', name: 'app_kitchen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, KitchenRepository $kitchenRepository, Member $member): Response
    {
        $kitchen = new Kitchen();
        $kitchen->setOwner($member);
        $form = $this->createForm(KitchenType::class, $kitchen);
        $form->handleRequest($request);

        $id = $member->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $kitchenRepository->add($kitchen, true);

            return $this->redirectToRoute('app_member_show', ['id' => $id], Response::HTTP_SEE_OTHER);
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


    #[Route('/{kitchen_id}/cookbook/{cookbook_id}', name: "app_kitchen_cookbook_show", methods:['GET'])]
    #[ParamConverter('kitchen', options: ['id' => 'kitchen_id'])]
    #[ParamConverter('cookbook', options: ['id' => 'cookbook_id'])]
    public function cookbookShow(Kitchen $kitchen, Cookbook $cookbook): Response
    {
        if(! $kitchen->getBook()->contains($cookbook)) {
            throw $this->createNotFoundException("Couldn't find such a cookbook in this kitchen!");
        }

        if(! $kitchen->isPublished()) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }

        return $this->render('kitchen/cookbook_show.html.twig', [
            'cookbook' => $cookbook,
            'kitchen' => $kitchen
        ]);
    }



}
