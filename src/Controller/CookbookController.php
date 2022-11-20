<?php

namespace App\Controller;

use App\Entity\Cookbook;
use App\Entity\Member;
use App\Entity\Bookshelf;
use App\Form\CookbookType;
use App\Repository\CookbookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/cookbook')]
class CookbookController extends AbstractController
{
    #[Route('/', name: 'app_cookbook_index', methods: ['GET'])]
    public function index(CookbookRepository $cookbookRepository): Response
    {
        return $this->render('cookbook/index.html.twig', [
            'cookbooks' => $cookbookRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_cookbook_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CookbookRepository $cookbookRepository, Member $member): Response
    {
        $cookbook = new cookbook();
        $cookbook->setMember($member);
        $form = $this->createForm(CookbookType::class, $cookbook);
        $form->handleRequest($request);

        $id = $member->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $cookbookRepository->save($cookbook, true);

            return $this->redirectToRoute('app_member_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cookbook/new.html.twig', [
            'cookbook' => $cookbook,
            'form' => $form,
        ]);
    }

    /*#[Route('/new', name: 'app_cookbook_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CookbookRepository $cookbookRepository): Response
    {
        $cookbook = new Cookbook();
        $form = $this->createForm(CookbookType::class, $cookbook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cookbookRepository->save($cookbook, true);

            return $this->redirectToRoute('app_cookbook_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cookbook/new.html.twig', [
            'cookbook' => $cookbook,
            'form' => $form,
        ]);
    }*/

    #[Route('/{id}', name: 'app_cookbook_show', methods: ['GET'])]
    public function show(Cookbook $cookbook): Response
    {
        return $this->render('cookbook/show.html.twig', [
            'cookbook' => $cookbook,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cookbook_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cookbook $cookbook, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CookbookType::class, $cookbook);
        $form->handleRequest($request);
        $id = $cookbook->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cookbook_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cookbook/edit.html.twig', [
            'cookbook' => $cookbook,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cookbook_delete', methods: ['POST'])]
    public function delete(Request $request, Cookbook $cookbook, EntityManagerInterface $entityManager): Response
    {

        $member = $cookbook->getMember();
        $id = $member->getId();

        if ($this->isCsrfTokenValid('delete'.$cookbook->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cookbook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_member_show', [ 'id' => $id ], Response::HTTP_SEE_OTHER);
    }
}
