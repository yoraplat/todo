<?php

namespace Tests\Feature;

use App\Models\Todo; // Assuming your Todo model is in the "Models" namespace
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fetching todos by user ID.
     */
    public function testGetTodos(): void
    {
        $user = User::factory(1)->createOne();
        Todo::factory(10)->create(['user_id' => $user->id, 'is_completed' => false]);


        Sanctum::actingAs($user);

        $response = $this->get("/api/todos");
        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    /**
     * Test updating a todo.
     */
    public function testUpdateTodo(): void
    {
        $user = User::factory(1)->createOne();
        $todo = Todo::factory(10)->createOne(['user_id' => $user->id, 'is_completed' => false]);

        Sanctum::actingAs($user);

        $todo->is_completed = true;
        
        $response = $this->patch("api/todos/{$todo->id}", $todo->toArray());

        $response->assertStatus(200)->assertJsonFragment($todo->toArray());
    }

    /**
     * Test storing a new todo.
     */
    public function testStoreTodo(): void
    {
        $user = User::factory(1)->createOne();
        Sanctum::actingAs($user);

        $data = Todo::factory()->createOne()->toArray();

        $response = $this->post('/api/todos', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('todos', $data);
    }

    /**
     * Test deleting a todo.
     */
    public function testDeleteTodo(): void
    {
        $user = User::factory(1)->createOne();
        Sanctum::actingAs($user);

        $todo = Todo::factory()->createOne();

        $response = $this->delete("/api/todos/{$todo->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
    
    /**
     * Test searching for todo's.
     */
    public function testSearchTodo(): void
    {
        $user = User::factory(1)->createOne();
        Sanctum::actingAs($user);

        $todo = Todo::factory()->createOne(['title' => 'test todo number 1']);
        Todo::factory(9)->create();

        $searchTerm = urlencode($todo->title);
        $response = $this->get("/api/todos?search=" . $searchTerm);
        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }
}
