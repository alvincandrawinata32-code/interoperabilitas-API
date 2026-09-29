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
}
