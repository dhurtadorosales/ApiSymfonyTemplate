<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends Fixture
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
        $data = [
            'clark@kent.com', 'Clark', 'Kent', 'Metrópolis',
            'bruce@wayne.com', 'Bruce', 'Wayne', 'Gotham',
            'diana@prince.com', 'Diana', 'Prince', 'Themyscira'
        ];

        $users = [];

        foreach ($data as $item) {
            $user = new User();
            $user
                ->setEmail($item[0])
                ->setName($item[1])
                ->setLastName($item[2])
                ->setCity($item[3]);

            $manager->persist($user);
            array_push($users, $user);
        }

        $manager->flush();
    }
}