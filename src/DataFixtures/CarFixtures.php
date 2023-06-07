<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class CarFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 11; $i++) {

            $car = new Car();
            $car->setBrand($this->faker->company());
            $car->setModel($this->faker->word());
            $car->setSeats($this->faker->numberBetween($min = 2, $max = 7));
            $car->setCreated($this->faker->dateTimeBetween('-1 year', 'now'));

            // Récupérer une référence d'utilisateur comme propriétaire
            $userReference = $this->getReference('user_' . $i);
            $car->setOwner($userReference);

            $this->setReference("car_" . $i, $car);


            // Enregistre le produit fraîchement créé, à faire à chaque tour de boucle
            $manager->persist($car);
        }
        // Une fois la boucle terminée je persiste les produits fraîchement créés
        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}
