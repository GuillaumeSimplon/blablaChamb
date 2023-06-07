<?php
    namespace App\DataFixtures;

    use App\Entity\Ride;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Persistence\ObjectManager;
    use Faker\Factory;

class RideFixtures extends AbstractFixtures implements DependentFixtureInterface {

    public function load (ObjectManager $manager):void {

        for ($i=0; $i < 11; $i++) { 
            $ride = new Ride();
            $ride->setDeparture($this->faker->word());
            $ride->setDestination($this->faker->word());
            $ride->setSeats($this->faker->numberBetween($min = 2, $max = 7));
            $ride->setPrice(round($this->faker->randomFloat(), 2));
            $ride->setDate($this->faker->dateTimeBetween('-1 year', 'now'));
            $ride->setCreated($this->faker->dateTimeBetween('-1 year', 'now'));
            for ($e=0; $e < 3; $e++) { 
                $ride->addRule($this->getReference('rule_' . $this->faker->numberBetween($min = 0, $max = 10)));
            }

                // Récupérer une référence d'utilisateur comme propriétaire
                $userReference = $this->getReference('user_' . $i);
                $ride->setDriver($userReference);

                $ride->setCar($this->getReference("car_" . $this->faker->numberBetween(0, 10)));

                // Table intermediaire ride_rule
                $this->setReference('ride_' . $i, $ride);
                
            
            $manager->persist($ride);   
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class,
            RuleFixtures::class,
        ];
    }
}


?>