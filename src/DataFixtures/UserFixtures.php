<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;
    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'secret')
            );
            $manager->persist($user);
            $this->addReference("user$i", $user);
        }

        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordEncoder->encodePassword($admin, 'secret')
        );

        $manager->persist($admin);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupeUser'];
    }
}
