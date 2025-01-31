<?php

namespace App\Tests\App\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use App\Factory\TasksFactory;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tasks;


class TasksRepositoryTest extends KernelTestCase
{

    private EntityManagerInterface $entityManager;

    private TasksRepository $tasksRepository;

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //(2) use static::getContainer() to access the service container
        $container = static::getContainer();

        //(3) get TasksRepository from container.
        $this->tasksRepository = $container->get(TasksRepository::class);

        //$entity = TasksFactory::createOne(['name' => 'test1']);
        $entity = new Tasks;
        $entity->setName('test1');
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
  
        $this->assertNotNull($entity->getId());

        
        // query this post by PostRepository
        $byId = $this->tasksRepository->findOneBy(["id" => $entity->getId()]);
        $this->assertEquals("test1", $byId->getName());
       

    }

    

    
}
