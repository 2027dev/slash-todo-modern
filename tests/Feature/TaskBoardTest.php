<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_task_board(): void
    {
        $this->get('/')->assertStatus(200)->assertSeeLivewire('task-board');
    }

    public function test_it_adds_a_task(): void
    {
        Livewire::test('task-board')
            ->set('newTask', 'Buy flight to Portugal')
            ->call('addTask')
            ->assertSet('newTask', '');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Buy flight to Portugal',
            'completed' => false,
        ]);
    }

    public function test_it_does_not_add_a_blank_task(): void
    {
        Livewire::test('task-board')
            ->set('newTask', '   ')
            ->call('addTask');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_it_toggles_a_task_as_completed(): void
    {
        $task = Task::create(['title' => 'Learn about Slash', 'position' => 1]);

        Livewire::test('task-board')->call('toggle', $task->id);

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_it_removes_a_task(): void
    {
        $task = Task::create(['title' => 'Answer emails', 'position' => 1]);

        Livewire::test('task-board')->call('remove', $task->id);

        $this->assertDatabaseCount('tasks', 0);
    }
}
