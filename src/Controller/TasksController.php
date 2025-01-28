<?php

namespace App\Controller;


use App\Entity\Tasks;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\Uid\Uuid;



#[Route(path: "/api/tasks", name: "tasks_")]
class TasksController extends AbstractController 
implements LoggerAwareInterface
{

    private LoggerInterface $logger;

    /**
     * @param TasksRepository $tasks
     * @param EntityManagerInterface $objectManager
     
     */
    public function __construct(private readonly TasksRepository         $tasks,
                                private readonly EntityManagerInterface $objectManager)
    {
    }

    #[Route(path: "", name: "all", methods: ["GET"])]
    public function all() : Response
	{
		
		$tasks = $this->tasks->findAll();
		
		
		$data = [];
   
        foreach ($tasks as $task) {
           $data[] = [
               'id' => $task->getId(),
               'name' => $task->getName(),
               'description' => $task->getDesription(),
			   'status' => $task->getStatus()
			   
           ];
        }
		
		
		return $this->json($data);
		
		}

    
    #[Route(path: "/{id}", name: "byId", methods: ["GET"])]
    public function getById(int $id): Response
    {
		
        $tasks = $this->tasks->find($id);		
        if ($tasks) {
			
			
           $data = [
               'id' => $tasks->getId(),
               'name' => $tasks->getName(),
               'description' => $tasks->getDesription(),
			   'status' => $tasks->getStatus()
			   
           ];
       
			
            return $this->json($data);
        } else {
            $this->logger->debug("error:  Task was not found by id:" . $id);
            return $this->json(["error" => "Task was not found by id:" . $id], 404);
        }
    }

    #[Route('/{id}', name: 'tasks_delete', methods:['delete'] )]
    public function delete(int $id): JsonResponse
    {
        $tasks = $this->tasks->find($id);
   
        if (!$tasks) {
			$this->logger->debug("error:  No task found for id=" . $id);
            return $this->json([
               'error' =>'No task found for id=' . $id], 404);
        }
   
        $this->objectManager->remove($tasks);
        $this->objectManager->flush();
   
        return $this->json( [
               'message' =>'Deleted a task successfully with id =' . $id]);
    }
	
	#[Route('', name: 'tasks_create', methods:['post'] )]
    public function create(Request $request): JsonResponse
    {
        
   try{
	   
	   if (!$request || !$request->request->get('name') || !$request->request->get('description') ||!$request->request->get('status')){
					$this->logger->debug("error:  Data no valid");
					throw new \Exception();
				}
	   
        $task = new Tasks();
        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
		$task->setStatus($request->request->get('status'));
   
        $this->objectManager->persist($task);
        $this->objectManager->flush();
   
        $data =  [
               'id' => $task->getId(),
               'name' => $task->getName(),
               'description' => $task->getDesription(),
			   'status' => $task->getStatus()
        ];
           
        return $this->json($data);
		
	}catch (\Exception $e){
				$data = [
					'status' => 422,
					'errors' => "Data no valid",
				];
				return $this->json($data, 422);
			}	
		
    }
	
	#[Route('/{id}', name: 'tasks_update', methods:['put'] )]
    public function update(int $id,Request $request): JsonResponse
    {
        try{
	   
	   if (!$request || !$request->request->get('name') || !$request->request->get('description') ||!$request->request->get('status')){
					$this->logger->debug("error:  Data no valid");
					throw new \Exception();
				}
		
         $task = $this->tasks->find($id);
		 
		 
   
        if (!$task) {
            return $this->json([
               'error' =>'No task found for id=' . $id], 404);
        }
   
        $task->setName($request->request->get('name'));
        $task->setDescription($request->request->get('description'));
		$task->setStatus($request->request->get('status'));
        $this->objectManager->flush();
   
        $data =  [
               'id' => $task->getId(),
               'name' => $task->getName(),
               'description' => $task->getDesription(),
			   'status' => $task->getStatus()
        ];
           
        return $this->json($data);
		
		}catch (\Exception $e){
				$data = [
					'status' => 422,
					'errors' => "Data no valid",
				];
				return $this->json($data, 422);
			}	
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
	
}
