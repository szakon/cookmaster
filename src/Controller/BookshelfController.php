<?php

namespace App\Controller;

use App\Entity\Bookshelf;
use App\Form\BookshelfType;
use App\Repository\BookshelfRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bookshelf')]
class BookshelfController extends AbstractController
{
    #[Route('/', name: 'app_bookshelf_index', methods: ['GET'])]
    public function index(BookshelfRepository $bookshelfRepository): Response
    {
        return $this->render('bookshelf/index.html.twig', [
            'bookshelves' => $bookshelfRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bookshelf_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookshelfRepository $bookshelfRepository): Response
    {
        $bookshelf = new Bookshelf();
        $form = $this->createForm(BookshelfType::class, $bookshelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookshelfRepository->save($bookshelf, true);

            return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bookshelf/new.html.twig', [
            'bookshelf' => $bookshelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bookshelf_show', methods: ['GET'])]
    public function show(Bookshelf $bookshelf): Response
    {
        return $this->render('bookshelf/show.html.twig', [
            'bookshelf' => $bookshelf,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bookshelf_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bookshelf $bookshelf, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookshelfType::class, $bookshelf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bookshelf/edit.html.twig', [
            'bookshelf' => $bookshelf,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bookshelf_delete', methods: ['POST'])]
    public function delete(Request $request, Bookshelf $bookshelf, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bookshelf->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bookshelf);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bookshelf_index', [], Response::HTTP_SEE_OTHER);
    }
}
