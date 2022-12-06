<?php

namespace App\DataFixtures;

use App\Entity\TimeMachine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $timeMachineData = array(
            array('name' => 'XKCD Comic – Kill Hitler', 'resourceURL' => 'https://xkcd.com/1063/'),
            array('name' => 'YouTube Song – Time Machine', 'resourceURL' => 'https://www.youtube.com/watch?v=8zwEnNJumQ4')
        );

        foreach ($timeMachineData as $data) {
            $timeMachine = new TimeMachine();
            $timeMachine->setName($data['name']);
            $timeMachine->setResourceURL($data['resourceURL']);
            $manager->persist($timeMachine);
        }

        $manager->flush();
    }
}