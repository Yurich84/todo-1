<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;

class TodoTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_todo()
    {
        $this->actingAs($this->user)
            ->postJson(route('todos.store'), [
                'name' => 'Lorem',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('todos', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_todo()
    {
        $todo = Todo::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('todos.update', $todo->id), [
                'name' => 'Updated todo',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'name' => 'Updated todo',
        ]);
    }

    /** @test */
    public function show_todo()
    {
        $todo = Todo::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('todos.show', $todo->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $todo->name,
                ],
            ]);
    }

    /** @test */
    public function list_todo()
    {
        $todos = Todo::factory()->count(2)->create()->map(function ($todo) {
            return $todo->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('todos.index'))
            ->assertSuccessful()
            ->assertJson([
                'data' => $todos->toArray(),
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
                'links',
                'meta',
            ]);
    }

    /** @test */
    public function delete_todo()
    {
        $todo = Todo::factory()->create([
            'name' => 'Todo for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('todos.update', $todo->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
            'name' => 'Todo for delete',
        ]);
    }
}
