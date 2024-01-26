<?php

namespace App\DataFixtures;

use App\Entity\District;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DistrictFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Example District in Gdansk - Morena
        $district = new District();
        $district->setName('Morena');
        $district->setLandMass('2 sq km'); // Adjust the land mass accordingly
        $district->setPopulation('15000'); // Adjust the population accordingly
        $district->setCity('Gdansk');
        $district->setImagePath('https://picsum.photos/200/300');
        $manager->persist($district);

        // Example District in Krakow - Kazimierz
        $district = new District(); // Use the same variable
        $district->setName('Kazimierz');
        $district->setLandMass('1.5 sq km'); // Adjust the land mass accordingly
        $district->setPopulation('12000'); // Adjust the population accordingly
        $district->setCity('Krakow');
        $district->setImagePath('https://picsum.photos/200/300');
        $manager->persist($district);

        $manager->flush();
    }
}
