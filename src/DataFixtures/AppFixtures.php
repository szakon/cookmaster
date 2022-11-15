<?php

namespace App\DataFixtures;

use App\Entity\CuisineType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bookshelf;
use App\Entity\Cookbook;
use App\Entity\Member;


class AppFixtures extends Fixture
{

    private static function memberDataGenerator()
    {
        yield ["Thais", "j'adore la cuisine"];
        yield ["Lou Anne", "mes recettes favorit"];
        yield ["Sarah", "Bonjour"];
        dump("chargemember");
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

    private static function cookbookDataGenerator()
    {
        //yield [shelf, title, author, cuisine, year]
        yield ["Thais", "My favorites",  "Instant pot", "smith", "Comfort", 2018];
        yield ["Thais", "My favorites", "MOMOFUKU", "David Chang", "Noodles", 2010];
        yield ["Thais", "My comfort food", "Made in inda", "Meera Sodha", "Indian", 2014];
        yield ["Lou Anne", "Used daily", "Pinch of nom", "Catherine Allinson", "Comfort", 2021];
        yield ["Lou Anne", "Used daily", "Jane's patisserie", "Jane Dunn", "Pastry", 2021];
        yield ["Lou Anne", "For special occasions", "Season", "Nik Sharma", "Indian", 2018];
        yield ["Sarah", "Banger", "Nopalito", "Gonzalo Guzman", "Mexican", 2017];
        yield ["Sarah", "Banger", "The Silver spoon", "Domus", "Italian", 1950];
        yield ["Sarah", "Fancy", "Ramen Otaku", "Sarah Gavigan", "Noodles", 2018];


    }

    private static function cuisinetypeDataGenerator(){
        yield ["Noodles"];
        yield ["Indian"];
        yield ["Pastry"];
        yield ["Italian"];
        yield ["Mexican"];
        yield ["Comfort"];
    }


    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $memberRepo = $manager->getRepository(Member::class);
        foreach(self::memberDataGenerator() as [$name, $bio]) {
            $member = new Member();
            $member->setName($name);
            $member->setBio($bio);
            $manager->persist($member);
        }
        dump("etape1");
        $manager->flush();

        $bookshelfRepo = $manager->getRepository(Bookshelf::class);
        foreach(self::bookshelfDataGenerator() as [$name, $shelf]) {
            $member = $memberRepo->findOneBy(['Name' => $name]);
            $bookshelf = new Bookshelf();
            $bookshelf->setShelf($shelf);
            $member->addBookshelf($bookshelf);
            $manager->persist($member);
        }
        dump("etape2");
        $manager->flush();

        $cookbookRepo = $manager->getRepository(Cookbook::class);
        $cuisinetypeRepo = $manager->getRepository(CuisineType::class);
        foreach(self::cuisinetypeDataGenerator() as [$type]) {

            //$book = $cookbookRepo->findBy(['cuisine' => $type]);
            $cuisinetype = new CuisineType();
            $cuisinetype->setLabel($type);
            $manager->persist($cuisinetype);
        }
        dump("etape3");
        $manager->flush();

        foreach(self::cookbookDataGenerator() as [$name, $shelf, $title, $author, $cuisine, $year]) {

            $member = $memberRepo->findOneBy(['Name' => $name]);
            $bookshelf = $bookshelfRepo->findOneBy(['member' => $member, 'shelf' => $shelf]);
            $type = $cuisinetypeRepo->findOneBy(['label' => $cuisine]);
            $book = new Cookbook();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setCuisine($cuisine);
            $book->setYear($year);
            $type->addCookbook($book);
            $bookshelf->addCookbook($book);
            $manager->persist($type);
            $manager->persist($bookshelf);
        }
        dump("etape3");
        $manager->flush();




    }
}
