@php
    $done = $task->isCompleted();
    $overdue = $task->isOverdue();
    $dueToday = $task->isDueToday();
    $days = $task->due_date ? (int) abs($task->due_date->diffInDays(today())) : null;
@endphp

<li @class(['task-row flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:gap-6 sm:px-5', 'bg-overdue-wash/50' => $overdue])>
    <div class="min-w-0 flex-1">
        <p @class([
            'font-medium break-words',
            'text-ink-faint line-through decoration-ink-faint/60' => $done,
        ])>{{ $task->task_name }}</p>

        @if ($task->description)
            <p @class(['mt-1 line-clamp-2 text-sm break-words', 'text-ink-faint' => $done, 'text-ink-soft' => ! $done])>
                {{ $task->description }}
            </p>
        @endif

        <div class="mt-2.5 flex flex-wrap items-center gap-2 text-xs font-medium">
            @if ($overdue)
                <span class="inline-flex items-center gap-1.5 rounded-md bg-overdue px-2 py-1 text-white">
                    <x-icon name="alert" class="size-3.5" :stroke="2.25" />
                    Overdue · {{ $days }} {{ Str::plural('day', $days) }}
                </span>
                <span class="text-overdue tabular-nums">Due {{ $task->due_date->format('M j') }}</span>
            @elseif ($dueToday)
                <span class="inline-flex items-center gap-1.5 rounded-md bg-today-wash px-2 py-1 text-today">
                    <x-icon name="clock" class="size-3.5" :stroke="2.25" />
                    Due today
                </span>
            @elseif ($task->due_date)
                <span @class(['inline-flex items-center gap-1.5 tabular-nums', 'text-ink-faint' => $done, 'text-ink-soft' => ! $done])>
                    <x-icon name="calendar" class="size-3.5" />
                    Due {{ $task->due_date->format('M j, Y') }}
                    @if (! $done && $task->due_date->isFuture())
                        <span class="text-ink-faint">· in {{ $days }} {{ Str::plural('day', $days) }}</span>
                    @endif
                </span>
            @else
                <span class="text-ink-faint">No due date</span>
            @endif
        </div>
    </div>

    {{-- Actions: status dropdown, edit and delete share one row --}}
    <div class="flex shrink-0 items-center justify-between gap-3 sm:justify-end">
        {{-- Update status: picking an option saves it right away --}}
        <form method="POST" action="{{ route('tasks.status', $task) }}" data-status-form>
            @csrf
            @method('PATCH')
            <label for="status-{{ $task->id }}" class="sr-only">Status of “{{ $task->task_name }}”</label>
            <div class="relative">
                <select id="status-{{ $task->id }}" name="status"
                    @class([
                        'h-9 w-32 cursor-pointer appearance-none rounded-lg border py-0 pr-8 pl-3 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-3 focus-visible:ring-accent/20',
                        'border-accent/25 bg-accent-wash text-accent-strong hover:border-accent/50' => $done,
                        'border-line bg-surface text-ink-soft hover:border-ink-faint' => ! $done,
                    ])>
                    @foreach (\App\Models\Task::STATUSES as $option)
                        <option value="{{ $option }}" @selected($task->status === $option)>{{ $option }}</option>
                    @endforeach
                </select>
                <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-2.5 size-4 -translate-y-1/2 opacity-70" :stroke="2.25" />
            </div>
            <noscript><button type="submit" class="ml-1 underline">Save</button></noscript>
        </form>

        <div class="-mr-2 flex items-center gap-0.5">
            <a href="{{ route('tasks.edit', $task) }}"
                class="grid size-9 place-items-center rounded-lg text-ink-faint transition-colors hover:bg-canvas hover:text-ink"
                title="Edit" aria-label="Edit “{{ $task->task_name }}”">
                <x-icon name="pencil" class="size-4" />
            </a>
            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                data-confirm="Delete “{{ $task->task_name }}”? This can’t be undone.">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="grid size-9 cursor-pointer place-items-center rounded-lg text-ink-faint transition-colors hover:bg-overdue-wash hover:text-overdue"
                    title="Delete" aria-label="Delete “{{ $task->task_name }}”">
                    <x-icon name="trash" class="size-4" />
                </button>
            </form>
        </div>
    </div>
</li>
