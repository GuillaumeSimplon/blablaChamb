<?php
    namespace App\DataFixtures;

    use App\Entity\Rule;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Persistence\ObjectManager;
    use Faker\Factory;

class RuleFixtures extends AbstractFixtures implements DependentFixtureInterface {

    public function load (ObjectManager $manager):void {

        for ($i=0; $i < 11; $i++) { 
            $rule = new Rule();
            $rule->setName($this->faker->name());
            $rule->setDescription($this->faker->sentence(10));

                // Récupérer une référence d'utilisateur comme propriétaire
                $userReference = $this->getReference('user_' . $i);
                $rule->setAuthor($userReference);

                $this->setReference('rule_' . $i, $rule);
            
            $manager->persist($rule);   
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}


?>