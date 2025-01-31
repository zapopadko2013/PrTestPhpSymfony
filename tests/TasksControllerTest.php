<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Tasks;

use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class TasksControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        /*
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World');
        */

        $client = static::createClient();
        $crawler = $client->request('GET', '/api/tasks');

        $this->assertResponseIsSuccessful();

        //
        $response = $client->getResponse();
        $data = $response->getContent();
        $this->assertStringContainsString("Symfony and PHP", $data);
    }

    public function testWhenGettingNonExistingTasks_thenReturn404Status(): void
    {
        $client = static::createClient();
        $id = 800;
        $crawler = $client->jsonRequest('GET', '/api/tasks/' . $id);

        //
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $data = $response->getContent();
        //$this->assertStringContainsString("Tasks #" . $id . " was not found", $data);
        $this->assertStringContainsString('{"error":"Task was not found by id:800"}', $data);
    }

    public function testWhenUpdatingNonExistingTask_thenReturn404Status(): void
    {
        $client = static::createClient();
        $id = 800;
        
        $data = new Tasks;
        $data->setName('test1');
        $crawler = $client->request(
            'PUT',
            '/api/tasks/' . $id,
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            $this->getContainer()->get('serializer')->serialize($data, 'json')
        );

        //
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $data = $response->getContent();
        
        $this->assertStringContainsString("{\"error\":\"No task found for id=800\"}", $data);
    
        $data = new Tasks;
        //$data->setName('test1');
        $crawler = $client->request(
            'PUT',
            '/api/tasks/' . $id,
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            $this->getContainer()->get('serializer')->serialize($data, 'json')
        );

        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(422);
        $data = $response->getContent();
        
        $this->assertStringContainsString('{"status":422,"errors":"Data no valid"}', $data);
    

    }

    public function testWhenDeletingNonExistingTask_thenReturn404Status(): void
    {
        $client = static::createClient();
        $id = 800;
        $crawler = $client->request(
            'DELETE',
            '/api/tasks/' . $id);

        //
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(404);
        $data = $response->getContent();
        $this->assertStringContainsString("No task found for id=" . $id , $data);
    }

    public function testTaskCrudFlow(): void
    {
        $client = static::createClient();

        // 1. create a new tasks
        
        $data = new Tasks;
        $data->setName('test188');
        $d=['id' => $data->getId(),'name' => $data->getName()];
        $crawler = $client->request(
            'POST',
            '/api/tasks',
            $d
            
        );

       

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $response = $client->getResponse();
        $url = '/api/tasks';
       


        // 2. get the newly created tasks.
       
        $response1 = $client->jsonRequest('GET', $url."/1",);

        $getByIdResponse = $client->getResponse();
        echo("json response:::" . $getByIdResponse->getContent());
        $this->assertEquals('{"id":1,"name":"test1qw","description":null,"status":null}', $getByIdResponse->getContent());
       
        $data = new Tasks;
        $data->setName('test1qw');
        $client->request(
            'PUT',
            $url."/1",
            [],
            [],
            ["CONTENT_TYPE" => "application/json"],
            $this->getContainer()->get('serializer')->serialize($data, 'json')
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        
        // 4. verify the updated tasks.
        $client->jsonRequest('GET', $url."/1",);

        $updatedResponse = $client->getResponse();
        echo("json response of updated post:::" . $updatedResponse->getContent());
        $this->assertEquals('{"id":1,"name":"test1qw","description":null,"status":null}', $updatedResponse->getContent());
        
        
        
        // 8. delete the tasks
        $client->jsonRequest("DELETE", $url."/124");
        $client->getResponse();
        $this->assertResponseStatusCodeSame(200);

        // 9. verify the tasks is deleted.
        $client->jsonRequest('GET', $url."/124",);
        $this->assertResponseStatusCodeSame(404);
        
    }

}
