<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_dapat_melihat_daftar_task()
    {
        //Arrange
        $tasks = Task::factory()->count(1)->create();

        //Act
        $response = $this->getJson('/api/v1/tasks');

        //Assert
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            'all' => [
                '*' => [
                    'id',
                    'name',
                    'is_completed',
                ]
            ]
        ]);
    }

    public function test_user_dapat_melihat_satu_task()
    {
        //Arrange
        $task = Task::factory()->create();

        //Act
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        //Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_completed',
            ]
        ]);
    }

    public function test_user_dapat_melihat_1_task()
    {
        //Arrange
        $task = Task::factory()->create();

        //Act
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        //Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_completed',
            ]
        ]);
    }

    public function test_user_dapat_membuat_task()
    {
        //Arrange
        $taskData = [
            'name' => 'Task Baru',
            'is_completed' => false,
        ];

        //Act
        $response = $this->postJson('/api/v1/tasks', $taskData);

        //Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_completed',
            ]
        ]);
    }

    public function test_user_dapat_memperbarui_task()
    {
        //Arrange
        $task = Task::factory()->create();
        $updateData = [
            'name' => 'Task Diperbarui',
            'is_completed' => true,
        ];

        //Act
        $response = $this->putJson('/api/v1/tasks/' . $task->id, $updateData);

        //Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_completed',
            ]
        ]);
    }

    public function test_user_dapat_menghapus_task()
    {
        //Arrange
        $task = Task::factory()->create();

        //Act
        $response = $this->deleteJson('/api/v1/tasks/' . $task->id);

        //Assert
        $response->assertStatus(204);
    }
}
