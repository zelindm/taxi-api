<?php

namespace App\DataFixtures;

use App\Entity\Airport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $airports = [
            'Heathrow' => [],
            'Gatwick'  => [
                '1',
                '2',
                '3',
                '4',
                'Not sure',
            ],
        ];

        foreach ($airports as $airportName => $terminals) {
            $a = new Airport();
            $a->setName($airportName);
            $a->setAvailibleTerminals($terminals);
            $manager->persist($a);
        }

        $manager->flush();
    }
}
