<?php


namespace CoreBundle\DataFixtures\ORM;


use ApiBundle\Entity\Customers;
use ApiBundle\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class LoadProducts extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $customers = new Customers();
        $faker = Faker\Factory::create();

        for ($i=0; $i < 10; $i++) {
            $products = new Products();
            $products->setIssn($faker->numberBetween(10000000,99999999));
            $products->setName($faker->jobTitle);
            $products->setStatus("new");
            $products->setCreatedAt($faker->dateTime);
            $products->setUpdatedAt($faker->dateTime);
            $products->setDeletedAt($faker->dateTime);
//            $products->setCustomer();

            $products->setCustomer($customers->getId());

            $manager->persist($products);
        }

        $manager->flush();

    }
}