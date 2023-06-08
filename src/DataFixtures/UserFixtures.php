<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        $adminUser = new User();
        $adminUser->setEmail('janolapin@gmail.com');
        $adminUser->setRoles(["USER"]);
        $adminUser->setPhone('0123456789');
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, '123456'));
        
        $adminUser->setCreated(new \DateTime());

        $adminUser->setFirstName('Jano');
        $adminUser->setLastName('Lapin');

        $manager->persist($adminUser);

        $this->setReference('user_0', $adminUser);


        for ($i = 1; $i < 11; $i ++) {

            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, '123456'));
            $user->setRoles(["USER"]);
            $user->setFirstName($this->faker->firstName());
            $user->setLastName($this->faker->lastName());
            $user->setPhone($this->faker->phoneNumber());
            $user->setCreated($this->faker->dateTimeBetween('-1 year', 'now'));
            


						// Enregistre le produit fraîchement créé, à faire à chaque tour de boucle
            $this->setReference('user_' . $i, $user);
            $manager->persist($user);
                        
        }

				// Une fois la boucle terminée je persiste les produits fraîchement créés
        $manager->flush();
    }
}
