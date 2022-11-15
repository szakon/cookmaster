<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bookshelf;
use App\Entity\Cookbook;
use App\Entity\Member;


class AppFixtures extends Fixture
{

    private static function memberDataGenerator()
    {
        yield ["Thais"];
        yield ["Lou Anne"];
        yield ["Sarah"];
    }

    private static function bookshelfDataGenerator()
    {
        yield ["Thais", "My favorites"];
        yield ["Thais", "My comfort food"];
        yield ["Lou Anne", "Used daily"];
        yield ["Lou Anne", "For special occasions"];
        yield ["Sarah", "Banger"];
        yield ["Sarah", "Fancy"];
    }

    private static function recipebookDataGenerator()
    {
        //yield [shelf, title, author, cuisine, year]
        yield ["Thais", "My favorites",  "Instant pot", "smith", "Comfort", 2018];
        yield ["Thais", "My favorites", "MOMOFUKU", "David Chang", "Noodles", 2010];
        yield ["Thais", "My comfort food", "Made in inda", "Meera Sodha", "Indian", 2014];
        yield ["Lou Anne", "Used daily", "Pinch of nom", "Catherine Allinson", "Comfort", 2021];
        yield ["Lou Anne", "Used daily", "Jane's patisserie", "Jane Dunn", "Pastry", 2021];
        yield ["Lou Anne", "For special occasions", "Season", "Nik Sharma", "Indian", 2018];
        yield ["Sarah", "Banger", "Nopalito", "Gonzalo Guzman", "Mexican", 2017];
        yield ["Sarah", "Banger", "The Silver spoon", "Domus", "Italien", 1950];
        yield ["Sarah", "Fancy", "Ramen Otaku", "Sarah Gavigan", "Noodles", 2018];


    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $memberRepo = $manager->getRepository(Member::class);
        foreach(self::memberDataGenerator() as [$name]) {
            $member = new Member();
            $member->setName($name);
            $manager->persist($manager);
        }
        $manager->flush();

        $bookshelfRepo = $manager->getRepository(Bookshelf::class);
        foreach(self::bookshelfDataGenerator() as [$name, $shelf]) {
            $member = $memberRepo->findOneBy(['name' => $name]);
            $bookshelf = new Bookshelf();
            $bookshelf->setShelf($shelf);
            $member->addBookshelf($bookshelf);
            $manager->persist($member);
        }
        $manager->flush();

        foreach(self::cookbookDataGenerator() as [$member, $shelf, $title, $author, $cuisine, $year]) {

            $bookshelf = $bookshelfRepo->findOneBy(['member' =>$member, 'shelf' => $shelf]);
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
