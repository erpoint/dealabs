<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // create Users
        $users = Array();
        for ($i = 0; $i < 4; $i++) {
            $users[$i] = new User();
            $users[$i]->setLogin($faker->userName());
            $users[$i]->setPassword(password_hash($faker->password(), PASSWORD_BCRYPT, [ 'cost' => 13 ]));
            $users[$i]->setEmail($faker->email());
            $users[$i]->setRoles(array('USER'));
            $users[$i]->setCreatedAt(date_create_immutable($faker->dateTimeThisMonth()->format(DATE_ATOM)));
        }

        // create Deals
        $deals = Array();
        for ($i = 0; $i < 4; $i++) {
            $deals[$i] = new Deal();
            $deals[$i]->setLink(null);
            $deals[$i]->setImage($faker->imageUrl);
            $deals[$i]->setFullPrice($faker->randomFloat(2, 1, 250));
            $deals[$i]->setCurrentPrice($faker->randomFloat(
                2,
                $deals[$i]->getFullPrice() - $deals[$i]->getFullPrice()/2,
                $deals[$i]->getFullPrice()
            ));
            $deals[$i]->setDegres($faker->numberBetween($min = 0, $max = 500));
            $deals[$i]->setTitle($faker->realText(30));
            $deals[$i]->setDescription($faker->text(255));
            $deals[$i]->setCreatedAt(date_create_immutable($faker->dateTimeThisMonth()->format(DATE_ATOM)));

            // add foreign keys
            $posUser = $faker->numberBetween($min = 0, $max = 3);
            $deals[$i]->setOwner($users[$posUser]);
            $users[$posUser]->getDeals()->add($deals[$i]);
        }

        $comments = Array();
        for ($i = 0; $i < 4; $i++) {
            $comments[$i] = new Comment();
            $comments[$i]->setContent($faker->text(255));
            $comments[$i]->setCreatedAt(date_create_immutable($faker->dateTimeThisMonth()->format(DATE_ATOM)));

            // add foreign keys
            $posUser = $faker->numberBetween($min = 0, $max = 3);
            $comments[$i]->setTheuser($users[$posUser]);
            $users[$posUser]->getComments()->add($comments[$i]);
            $posDeal = $faker->numberBetween($min = 0, $max = 3);
            $comments[$i]->setDeal($deals[$posDeal]);
            $deals[$posDeal]->getComments()->add($comments[$i]);
        }

        foreach ($users as $user) {
            $manager->persist($user);
        }
        foreach ($deals as $deal) {
            $manager->persist($deal);
        }
        foreach ($comments as $comment) {
            $manager->persist($comment);
        }
        $manager->flush();
    }
}
