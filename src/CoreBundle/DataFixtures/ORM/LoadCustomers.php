<?php

namespace CoreBundle\DataFixtures\ORM;

use ApiBundle\Entity\Customers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class LoadCustomers extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i < 10; $i++) {
            $customers = new Customers();
            $customers->setUuid($faker->uuid);
            $customers->setFirstName($faker->firstName());
            $customers->setLastName($faker->lastName());
            $customers->setDateOfBirth($faker->dateTime);
            $customers->setStatus("new");
            $customers->setCreatedAt($faker->dateTime);
            $customers->setUpdatedAt($faker->dateTime);
            $customers->setDeletedAt($faker->dateTime);
            $manager->persist($customers);
        }

        $manager->flush();

    }
}