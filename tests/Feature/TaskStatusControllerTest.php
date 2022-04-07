<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('task_statuses.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $taskStatus = TaskStatus::factory()->make()->toArray();
        $response = $this->actingAs($this->user)
            ->post(route('task_statuses.store'), $taskStatus);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $taskStatus);
    }

    public function testEdit(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $response = $this->actingAs($this->user)
            ->get(route('task_statuses.edit', [$taskStatus]));
        $response->assertOk();
    }

    public function testUpdate(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $newTaskStatus = $taskStatus->only(['id', 'name']);
        $response = $this->actingAs($this->user)
            ->patch(route('task_statuses.update', $taskStatus), $newTaskStatus);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', $newTaskStatus);
    }

    public function testDestroy(): void
    {
        $taskStatus = TaskStatus::factory()->create();
        $response = $this->actingAs($this->user)
            ->delete(route('task_statuses.destroy', [$taskStatus]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseMissing('task_statuses', ['id' => $taskStatus['id']]);
    }
}
