<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_tasks(): void
    {
        Task::create(['task_name' => 'Read chapter 2', 'status' => Task::PENDING]);

        $this->get('/tasks')->assertOk()->assertSee('Read chapter 2');
    }

    public function test_a_task_can_be_added(): void
    {
        $this->post('/tasks', [
            'task_name' => 'Write essay',
            'description' => 'Two pages',
            'status' => Task::PENDING,
            'due_date' => '2026-10-01',
        ])->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['task_name' => 'Write essay', 'status' => 'Pending']);
    }

    public function test_task_name_is_required(): void
    {
        $this->post('/tasks', ['task_name' => '', 'status' => Task::PENDING])
            ->assertSessionHasErrors('task_name');
    }

    public function test_a_task_can_be_edited(): void
    {
        $task = Task::create(['task_name' => 'Old name', 'status' => Task::PENDING]);

        $this->put("/tasks/{$task->id}", [
            'task_name' => 'New name',
            'status' => Task::COMPLETED,
        ])->assertRedirect('/tasks');

        $this->assertSame('New name', $task->fresh()->task_name);
        $this->assertTrue($task->fresh()->isCompleted());
    }

    public function test_a_task_can_be_deleted(): void
    {
        $task = Task::create(['task_name' => 'Delete me', 'status' => Task::PENDING]);

        $this->delete("/tasks/{$task->id}")->assertRedirect();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_status_can_be_changed_from_the_dropdown(): void
    {
        $task = Task::create(['task_name' => 'Change me', 'status' => Task::PENDING]);

        $this->patch("/tasks/{$task->id}/status", ['status' => Task::COMPLETED]);
        $this->assertSame(Task::COMPLETED, $task->fresh()->status);

        $this->patch("/tasks/{$task->id}/status", ['status' => Task::PENDING]);
        $this->assertSame(Task::PENDING, $task->fresh()->status);
    }

    public function test_status_must_be_pending_or_completed(): void
    {
        $task = Task::create(['task_name' => 'Change me', 'status' => Task::PENDING]);

        $this->patch("/tasks/{$task->id}/status", ['status' => 'Maybe'])
            ->assertSessionHasErrors('status');

        $this->assertSame(Task::PENDING, $task->fresh()->status);
    }

    public function test_only_pending_tasks_past_their_due_date_are_overdue(): void
    {
        $late = Task::create(['task_name' => 'Late', 'status' => Task::PENDING, 'due_date' => today()->subDay()]);
        Task::create(['task_name' => 'Late but done', 'status' => Task::COMPLETED, 'due_date' => today()->subDay()]);
        Task::create(['task_name' => 'Due today', 'status' => Task::PENDING, 'due_date' => today()]);
        Task::create(['task_name' => 'No date', 'status' => Task::PENDING]);

        $this->assertTrue($late->isOverdue());
        $this->assertSame(['Late'], Task::overdue()->pluck('task_name')->all());

        $this->get('/tasks?filter=overdue')->assertSee('Late')->assertDontSee('Late but done');
    }
}
