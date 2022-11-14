<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bookshelf;
use App\Entity\Cookbook;

class AppFixtures extends Fixture
{

    private static function bookshelfDataGenerator()
    {
        yield ["My favorites"];
        yield ["My comfort food"];
        yield ["Used daily"];
        yield ["For special occasions"];
        yield ["Banger"];
        yield ["Fancy"];
    }

    private static function cookbookDataGenerator()
    {
        //yield [librayOwner, title, author, cuisine, year]
        yield ["My favorites",  "Instant pot", "smith", "Comfort", 2018];
        yield ["My favorites", "MOMOFUKU", "David Chang", "Noodles", 2010];
        yield ["My comfort food", "Made in inda", "Meera Sodha", "Indian", 2014];
        yield ["Used daily", "Pinch of nom", "Catherine Allinson", "Comfort", 2021];
        yield ["Used daily", "Jane's patisserie", "Jane Dunn", "Pastry", 2021];
        yield ["For special occasions", "Season", "Nik Sharma", "Indian", 2018];
        yield ["Banger", "Nopalito", "Gonzalo Guzman", "Mexican", 2017];
        yield ["Banger", "The Silver spoon", "Domus", "Italien", 1950];
        yield ["Fancy", "Ramen Otaku", "Sarah Gavigan", "Noodles", 2018];


    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $bookshelfRepo = $manager->getRepository(Bookshelf::class);
        foreach(self::bookshelfDataGenerator() as [$shelf]) {
            $bookshelf = new Bookshelf();
            $bookshelf->setShelf($shelf);
            $manager->persist($bookshelf);
        }
        $manager->flush();

        foreach(self::cookbookDataGenerator() as [$shelf, $title, $author, $cuisine, $year]) {

            $bookshelf = $bookshelfRepo->findOneBy(['shelf' => $shelf]);
            $book = new Cookbook();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setCuisine($cuisine);
            $book->setYear($year);
            $bookshelf->addCookBook($book);
            $manager->persist($bookshelf);
        }
        $manager->flush();



    }
}
