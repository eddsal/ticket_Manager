<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixture extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->getRoles(['ROLE_ADMIN']);
        $user->setPassword(
                $this->encoder->encodePassword($user,'0000')
        );
        $user->setEmail('eddysaliby@gmail.com');

        $manager->persist($user);

        $manager->flush();
    }
}
