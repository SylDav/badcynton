<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('BadcyntonTest' . $i . '@yopmail.fr');
            $user->setFirstname('BadcyntonTest' . $i);
            $user->setName('User' . $i);
            $user->setPassword($this->encoder->encodePassword($user, 'BadcyntonTest' . $i));
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('admin@admin.fr');
        $user->setFirstname('Sylvain');
        $user->setName('TestAdmin');
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->flush();
    }
}
