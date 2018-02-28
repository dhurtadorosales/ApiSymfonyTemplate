<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Alias;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadData extends Fixture
{
    /**
     * @var ContainerInterface
     */
    public $container;
    private $encoder;

    /**
     * LoadData constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $usersData = [
            ['admin@admin.com', 'admin', 'Admin', 'Admin', true, true],
            ['clark@kent.com', 'clark', 'Clark', 'Kent', false, true],
            ['bruce@wayne.com', 'bruce', 'Bruce', 'Wayne', false, true],
            ['diana@prince.com', 'diana', 'Diana', 'Prince', false, true]
        ];

        $users = [];

        foreach ($usersData as $item) {
            $user = new User();
            $password = $this->encoder->encodePassword($user, $item[1]);

            $user
                ->setEmail($item[0])
                ->setPass($password)
                ->setName($item[2])
                ->setLastName($item[3])
                ->setAdmin($item[4])
                ->setActive($item[5]);

            $manager->persist($user);
            array_push($users, $user);
        }

        $aliasData = [
            ['Superman', 'Metrópolis', $users[1]],
            ['Kal-El', 'Krypton', $users[1]],
            ['Batman', 'Gotham', $users[2]],
            ['Wonder Woman', 'Themyscira', $users[3]]
        ];

        $aliases = [];

        foreach ($aliasData as $item) {
            $alias = new Alias();
            $alias
                ->setName($item[0])
                ->setOrigin($item[1])
                ->setUser($item[2]);

            $manager->persist($alias);
            array_push($aliases, $alias);
        }

        $manager->flush();
    }
}