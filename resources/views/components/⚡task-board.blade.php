<?php

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $newTask = '';

    #[Computed]
    public function pendingTasks()
    {
        return Task::where('completed', false)->orderBy('position')->orderBy('id')->get();
    }

    #[Computed]
    public function completedTasks()
    {
        return Task::where('completed', true)->orderBy('id')->get();
    }

    public function addTask(): void
    {
        $title = trim($this->newTask);

        if ($title === '') {
            return;
        }

        Task::create([
            'title' => $title,
            'position' => (Task::max('position') ?? 0) + 1,
        ]);

        $this->newTask = '';
    }

    public function toggle(Task $task): void
    {
        $task->update(['completed' => ! $task->completed]);
    }

    public function remove(Task $task): void
    {
        $task->delete();
    }
};
?>

<div class="mx-auto flex min-h-screen max-w-xl flex-col justify-center px-6 py-16">
    <p class="mb-8 text-sm font-medium text-neutral-500">Today</p>

    <div class="space-y-5">
        @forelse ($this->pendingTasks as $task)
            <div wire:key="task-{{ $task->id }}" class="group flex items-center justify-between gap-4">
                <button
                    type="button"
                    wire:click="toggle({{ $task->id }})"
                    class="flex-1 text-left text-lg font-semibold text-white transition hover:text-neutral-300 cursor-pointer"
                >
                    {{ $task->title }}
                </button>
                <button
                    type="button"
                    wire:click="remove({{ $task->id }})"
                    class="text-neutral-600 opacity-0 transition hover:text-red-400 group-hover:opacity-100 cursor-pointer"
                    aria-label="Delete task"
                >
                    &times;
                </button>
            </div>
        @empty
            <p class="text-lg text-neutral-600">Nothing here yet — add your first task below.</p>
        @endforelse

        <form wire:submit="addTask" class="pt-2">
            <input
                type="text"
                wire:model="newTask"
                placeholder="Add task..."
                autocomplete="off"
                class="w-full border-0 border-b border-neutral-700 bg-transparent pb-3 text-lg font-medium text-neutral-300 placeholder-neutral-600 focus:border-violet-500 focus:outline-none focus:ring-0"
            >
        </form>
    </div>

    <button
        type="button"
        wire:click="addTask"
        class="mt-10 w-full rounded-lg bg-violet-600 py-4 text-lg font-semibold text-white transition hover:bg-violet-500 active:bg-violet-700 cursor-pointer"
    >
        Start Slashing
    </button>

    @if ($this->completedTasks->isNotEmpty())
        <div class="mt-12 space-y-4 border-t border-neutral-800 pt-8">
            <p class="text-sm font-medium text-neutral-600">Slashed</p>
            @foreach ($this->completedTasks as $task)
                <div wire:key="done-{{ $task->id }}" class="group flex items-center justify-between gap-4">
                    <button
                        type="button"
                        wire:click="toggle({{ $task->id }})"
                        class="flex-1 text-left text-lg font-semibold text-neutral-600 line-through transition hover:text-neutral-500 cursor-pointer"
                    >
                        {{ $task->title }}
                    </button>
                    <button
                        type="button"
                        wire:click="remove({{ $task->id }})"
                        class="text-neutral-700 opacity-0 transition hover:text-red-400 group-hover:opacity-100 cursor-pointer"
                        aria-label="Delete task"
                    >
                        &times;
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
