<?php

namespace App\Tests\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function testIndexPageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testIndexTrick()
    {
        $tricks = $this->entityManager
            ->getRepository(Trick::class)
            ->findAll()
        ;
        $this->assertIsArray($tricks);
    }

    public function testTrickName()
    {
        $trick = $this->entityManager
            ->getRepository(Trick::class)
            ->findOneBy(['id' => 1])
        ;
        $this->assertSame('Mute', $trick->getName());
    }

    public function testTrickIsActive()
    {
        $trick = $this->entityManager
            ->getRepository(Trick::class)
            ->findOneBy(['id' => 1])
        ;
        $this->assertTrue($trick->getActive());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
