<?php

namespace Aureja\Bundle\JobQueueBundle\Tests\Functional;

use Aureja\Bundle\JobQueueBundle\Tests\App\Entity\JobConfiguration;
use Aureja\Bundle\JobQueueBundle\Tests\App\Entity\JobReport;
use Aureja\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class JobQueueTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    protected function setUp()
    {
        $this->initClient();

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->createSchema();
    }

    public function testRun()
    {

    }

    private function createSchema()
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema(
            [
                $this->em->getClassMetadata(JobConfiguration::class),
                $this->em->getClassMetadata(JobReport::class),
            ]
        );

    }
}
