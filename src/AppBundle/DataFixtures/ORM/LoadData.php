<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Alias;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData extends Fixture
{
    /**
     * @var ContainerInterface
     */
    public $container;

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
            ['clark@kent.com', 'clark', 'Clark', 'Kent', true, true],
            ['bruce@wayne.com', 'bruce', 'Bruce', 'Wayne', false, true],
            ['diana@prince.com', 'diana', 'Diana', 'Prince', false, true]
        ];

        $users = [];

        foreach ($usersData as $item) {
            $user = new User();
            $user
                ->setEmail($item[0])
                ->setPass($item[1])
                ->setName($item[2])
                ->setLastName($item[3])
                ->setAdmin($item[4])
                ->setActive($item[5]);

            $manager->persist($user);
            array_push($users, $user);
        }

        $aliasData = [
            ['Superman', 'Metrópolis', $users[0]],
            ['Kal-El', 'Krypton', $users[0]],
            ['Batman', 'Gotham', $users[1]],
            ['Wonder Woman', 'Themyscira', $users[2]]
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