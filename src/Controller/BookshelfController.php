<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bookshelf;

class BookshelfController extends AbstractController
{
    /**
     * Controleur Bookshelf
     * @Route("/bookshelf")
     */
    public function indexbookshelf(): Response
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome!</title>
    </head>
    <body>
        <h1>Welcome</h1>
            
    <p>Welcome to our bookshelf</p>
    </body>
</html>';
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
    }

    /**
     * Lists of all bookshop entities
     *
     * @Route("/bookshelflist", name = "bookshelf_list", methods = "GET")
     * @Route("/bookshelfindex", name = "bookshelf_index", methods = "GET")
     */

    public function listAction(ManagerRegistry $doctrine): Response
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>bookshelf list!</title>
    </head>
    <body>
        <h1>bookshelf list</h1>
        <p>Here are all your bookshelves:</p>
        <ul>';

        $entityManager= $doctrine->getManager();
        $bookshelves = $entityManager->getRepository(Bookshelf::class)->findAll();
        foreach($bookshelves as $bookshelf) {
            $htmlpage .= '<li>
            <a href="/bookshelf/'.$bookshelf->getId().'">'.$bookshelf->getShelf().' "('.$bookshelf->getId().')"</a></li>';
        }
        $htmlpage .= '</ul>';

        $htmlpage .= '</body></html>';

        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
    }

    /**
     * Show a bookshelf
     *
     * @Route("/bookshelf/{id}", name="bookshelf_show", requirements={"id"="\d+"})
     *    note that the id must be an integer, above
     *
     * @param Integer $id
     */
    public function show(ManagerRegistry $doctrine, $id)
    {
        $bookshelfRepo = $doctrine->getRepository(Bookshelf::class);
        $bookshelf = $bookshelfRepo->find($id);

        if (!$bookshelf) {
            throw $this->createNotFoundException('The bookshelf does not exist');
        }

        $res = '<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>bookshelf '.$bookshelf->getShelf().' details</title>
 
</head>
<body>
<h2> '.$bookshelf->getShelf().' : </h2>
<ul><dl>';

        //Author, cuisine, year
        foreach($bookshelf->getCookBooks() as $cookbook) {
            $res .= '<dt>COOKBOOK</dt><dd>' . $cookbook->__toString() . '</dd>';
        }
        $res .= '</dl>';
        $res .= '</ul></body></html>';


        $res .= '<p/><a href="' . $this->generateUrl('bookshelf_index') . '">Back</a>';

        return new Response('<html><body>'. $res . '</body></html>');
    }

}

