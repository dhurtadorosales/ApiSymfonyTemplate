<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Alias;
use UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData extends Fixture
{
    /** @var ContainerInterface $container */
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
            ['Admin', 'Admin', 'admin', 'admin@admin.com', 'admin', true, ['ROLE_ADMIN']],
            ['Clark', 'Kent', 'clark', 'clark@kent.com', 'clark', true, ['ROLE_USER']],
            ['Bruce', 'Wayne', 'bruce', 'bruce@wayne.com', 'bruce', true, ['ROLE_USER']],
            ['Diana', 'Prince', 'diana', 'diana@prince.com', 'diana', true, ['ROLE_USER']],
            ['Steve', 'Rogers', 'steve', 'steve@rogers.com', 'steve', false, ['ROLE_USER']]
        ];

        $users = [];

        foreach ($usersData as $item) {
            $user = new User();
            $user
                ->setName($item[0])
                ->setLastName($item[1])
                ->setUsername($item[2])
                ->setEmail($item[3])
                ->setPlainPassword($item[4])
                ->setEnabled($item[5])
                ->setRoles($item[6]);

            $manager->persist($user);
            array_push($users, $user);
        }

        $aliasData = [
            ['Superman', 'Metrópolis', $users[1]],
            ['Kal-El', 'Krypton', $users[1]],
            ['Batman', 'Gotham', $users[2]],
            ['Wonder Woman', 'Themyscira', $users[3]],
            ['Captain America', 'New York', $users[4]]
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