<?php

namespace App\DataFixtures;

use App\Entity\CuisineType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bookshelf;
use App\Entity\Cookbook;
use App\Entity\Member;
use App\Entity\Kitchen;


class AppFixtures extends Fixture
{


    private static function memberDataGenerator()
    {
        yield ["Thais", "j'adore la cuisine"];
        yield ["Lou Anne", "mes recettes favorites"];
        yield ["Sarah", "Bonjour"];
        dump("chargemember");
    }

    /*private static function kitchenGenerator(){
        yield ["Thais", "Home"];
        yield ["Lou Anne", "Cookit"];
        yield ["Sarah", "Sarah's"];
    }*/

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
        yield ["Thais", "Thais", "My favorites",  "Instant pot", "smith", "Comfort", 2018, "Home"];
        yield ["Thais", "Thais", "My favorites", "MOMOFUKU", "David Chang", "Noodles", 2010, "Home"];
        yield ["Thais", "Thais", "My comfort food", "Made in inda", "Meera Sodha", "Indian", 2014, "Home"];
        yield ["Lou Anne", "Lou Anne", "Used daily", "Pinch of nom", "Catherine Allinson", "Comfort", 2021, "Cookit"];
        yield ["Lou Anne", "Lou Anne", "Used daily", "Jane's patisserie", "Jane Dunn", "Pastry", 2021, "Cookit"];
        yield ["Lou Anne", "Lou Anne", "For special occasions", "Season", "Nik Sharma", "Indian", 2018, "Cookit"];
        yield ["Sarah", "Sarah", "Banger", "Nopalito", "Gonzalo Guzman", "Mexican", 2017, "Sarah's"];
        yield ["Sarah", "Sarah", "Banger", "The Silver spoon", "Domus", "Italian", 1950, "Sarah's"];
        yield ["Sarah", "Sarah", "Fancy", "Ramen Otaku", "Sarah Gavigan", "Noodles", 2018, "Sarah's"];


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
        //$kitchenRepo = $manager->getRepository(Kitchen::class);
        foreach(self::cuisinetypeDataGenerator() as [$type]) {

            //$book = $cookbookRepo->findBy(['cuisine' => $type]);
            $cuisinetype = new CuisineType();
            $cuisinetype->setLabel($type);
            $manager->persist($cuisinetype);
        }
        dump("etape3");
        $manager->flush();

        /*foreach(self::kitchenGenerator() as [$owner, $name]) {


            $member = $memberRepo->findOneBy(['Name'=>$owner]);
            $kitchen = new Kitchen();
            $kitchen->setName($name);
            $kitchen->setOwner($owner);
            $member->addKitchen($kitchen);
        }*/

        foreach(self::cookbookDataGenerator() as [$member, $name, $shelf, $title, $author, $cuisine, $year, $kname]) {

            $member = $memberRepo->findOneBy(['Name' => $name]);
            $bookshelf = $bookshelfRepo->findOneBy(['member' => $member, 'shelf' => $shelf]);
            $type = $cuisinetypeRepo->findOneBy(['label' => $cuisine]);
            //$kitchen = $kitchenRepo->findOneBy(['name' => $kname]);
            $book = new Cookbook();
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setCuisine($cuisine);
            $book->setYear($year);
            $book->setMember($member);
            //$kitchen->addCookbook($book);
            $type->addCookbook($book);
            $bookshelf->addCookbook($book);
            //$manager->persist($kitchen);
            $member->addCookbook($book);
            $manager->persist($member);
            $manager->persist($type);
            $manager->persist($bookshelf);
        }
        dump("etape3");
        $manager->flush();




    }
}
