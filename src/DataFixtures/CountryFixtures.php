<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $usaStates = [
            "AL" => 'Alabama',
            "AK" => 'Alaska',
            "AZ" => 'Arizona',
            "AR" => 'Arkansas',
            "CA" => 'California',
        ];
        $country1 = new Country();
        $country1->setCode('geo');
        $country1->setName('Georgia');
        $country1->setStates([]);

        $country2 = new Country();
        $country2->setCode('ind');
        $country2->setName('India');
        $country2->setStates([]);

        $country3 = new Country();
        $country3->setCode('usa');
        $country3->setName('United States of America');
        $country3->setStates($usaStates);

        $country4 = new Country();
        $country4->setCode('ger');
        $country4->setName('Germany');
        $country4->setStates([]);

        $manager->persist($country1);
        $manager->persist($country2);
        $manager->persist($country3);
        $manager->persist($country4);
        $manager->flush();
    }
}
