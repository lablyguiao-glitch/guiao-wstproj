<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const FILTERS = ['all', 'pending', 'overdue', 'completed'];

    /**
     * Dashboard: show all tasks, optionally filtered.
     */
    public function index(Request $request): View
    {
        $filter = in_array($request->query('filter'), self::FILTERS, true)
            ? $request->query('filter')
            : 'all';

        $query = match ($filter) {
            'pending' => Task::pending(),
            'overdue' => Task::overdue(),
            'completed' => Task::completed(),
            default => Task::query(),
        };

        // Pending before completed, then earliest due date first, undated last.
        $tasks = $query
            ->orderByRaw('CASE WHEN status = ? THEN 1 ELSE 0 END', [Task::COMPLETED])
            ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $counts = [
            'all' => Task::count(),
            'pending' => Task::pending()->count(),
            'overdue' => Task::overdue()->count(),
            'completed' => Task::completed()->count(),
        ];

        return view('tasks.index', compact('tasks', 'counts', 'filter'));
    }

    public function create(): View
    {
        return view('tasks.create', ['task' => new Task(['status' => Task::PENDING])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $task = Task::create($this->validated($request));

        return redirect()->route('tasks.index')
            ->with('success', "Added “{$task->task_name}”.");
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validated($request));

        return redirect()->route('tasks.index')
            ->with('success', "Saved changes to “{$task->task_name}”.");
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return back()->with('success', "Deleted “{$task->task_name}”.");
    }

    /**
     * Set a task's status to Pending or Completed (from the dropdown).
     */
    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
        ]);

        $task->update($validated);

        $message = $task->isCompleted()
            ? "Marked “{$task->task_name}” as completed."
            : "Moved “{$task->task_name}” back to pending.";

        return back()->with('success', $message);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'task_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(Task::STATUSES)],
            'due_date' => ['nullable', 'date'],
        ], [
            'task_name.required' => 'Give the task a name.',
        ]);
    }
}
