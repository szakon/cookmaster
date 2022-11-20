<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/member')]
class MemberController extends AbstractController
{
    #[Route('/', name: 'app_member_index', methods: ['GET'])]
    public function index(MemberRepository $memberRepository): Response
    {
        return $this->render('member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_member_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MemberRepository $memberRepository): Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberRepository->save($member, true);

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('member/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('member/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_member_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_member_delete', methods: ['POST'])]
    public function delete(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_member_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{member_id}/cookbook/{cookbook_id}', name: "app_member_cookbook_show", methods:['GET'])]
    #[ParamConverter('member', options: ['id' => 'member_id'])]
    #[ParamConverter('cookbook', options: ['id' => 'cookbook_id'])]
    public function cookbookShow(member $member, cookbook $cookbook): Response
    {
        if(! $member->getcookbooks()->contains($cookbook)) {
            throw $this->createNotFoundException("Couldn't find such a cookbook in this member!");
        }


        return $this->render('member/cookbook_show.html.twig', [
            'cookbook' => $cookbook,
            'member' => $member
        ]);
    }

    #[Route('/{member_id}/bookshelf/{bookshelf_id}', name: "app_member_bookshelf_show", methods:['GET'])]
    #[ParamConverter('member', options: ['id' => 'member_id'])]
    #[ParamConverter('bookshelf', options: ['id' => 'bookshelf_id'])]
    public function bookshelfShow(member $member, bookshelf $bookshelf): Response
    {
        if(! $member->getbookshelfs()->contains($bookshelf)) {
            throw $this->createNotFoundException("Couldn't find such a bookshelf in this member!");
        }


        return $this->render('member/bookshelf_show.html.twig', [
            'bookshelf' => $bookshelf,
            'member' => $member
        ]);
    }


    #[Route('/{member_id}/kitchen/{kitchen_id}', name: "app_member_kitchen_show", methods:['GET'])]
    #[ParamConverter('member', options: ['id' => 'member_id'])]
    #[ParamConverter('kitchen', options: ['id' => 'kitchen_id'])]
    public function kitchenShow(member $member, kitchen $kitchen): Response
    {
        if(! $member->getkitchens()->contains($kitchen)) {
            throw $this->createNotFoundException("Couldn't find such a kitchen in this member!");
        }


        return $this->render('member/kitchen_show.html.twig', [
            'kitchen' => $kitchen,
            'member' => $member
        ]);
    }
}
