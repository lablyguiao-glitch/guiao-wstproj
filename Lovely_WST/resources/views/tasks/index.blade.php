@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $tabs = [
            'all' => 'All',
            'pending' => 'Pending',
            'overdue' => 'Overdue',
            'completed' => 'Completed',
        ];

        $emptyMessages = [
            'all' => ['No tasks yet', 'Add your first task to get started.'],
            'pending' => ['Nothing pending', 'Every task is done. Nice work.'],
            'overdue' => ['Nothing overdue', 'You’re on top of every deadline.'],
            'completed' => ['No completed tasks yet', 'Tick a task off and it will show up here.'],
        ];
    @endphp

    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Your tasks</h1>
    <p class="mt-2 text-ink-soft">
        {{ today()->format('l, F j') }}
        <span class="text-ink-faint">·</span>
        {{ $counts['pending'] }} {{ Str::plural('task', $counts['pending']) }} left to do
    </p>

    {{-- Filters double as the dashboard summary --}}
    <nav aria-label="Filter tasks" class="mt-8 grid grid-cols-2 gap-2 sm:grid-cols-4">
        @foreach ($tabs as $key => $label)
            @php
                $active = $filter === $key;
                $alert = $key === 'overdue' && $counts['overdue'] > 0;
            @endphp
            <a href="{{ route('tasks.index', $key === 'all' ? [] : ['filter' => $key]) }}"
                @if ($active) aria-current="page" @endif
                @class([
                    'group flex items-baseline justify-between gap-3 rounded-xl border px-4 py-3 transition-colors',
                    'border-ink bg-ink text-white' => $active,
                    'border-line bg-surface hover:border-ink-faint' => ! $active,
                ])>
                <span @class([
                    'text-sm font-medium',
                    'text-overdue' => $alert && ! $active,
                    'text-ink-soft group-hover:text-ink' => ! $alert && ! $active,
                ])>{{ $label }}</span>
                <span @class([
                    'text-2xl font-semibold tabular-nums tracking-tight',
                    'text-overdue' => $alert && ! $active,
                ])>{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    @if ($counts['overdue'] > 0 && ! in_array($filter, ['overdue', 'completed']))
        <div class="mt-6 flex flex-wrap items-center gap-x-3 gap-y-2 rounded-xl bg-overdue-wash px-4 py-3 text-sm">
            <x-icon name="alert" class="size-5 text-overdue" />
            <p class="min-w-48 flex-1 text-ink">
                <strong class="font-semibold text-overdue">
                    {{ $counts['overdue'] }} {{ Str::plural('task', $counts['overdue']) }} overdue.
                </strong>
                {{ $counts['overdue'] === 1 ? 'It’s' : 'They’re' }} past the due date and still pending.
            </p>
            <a href="{{ route('tasks.index', ['filter' => 'overdue']) }}"
                class="font-medium text-overdue underline decoration-overdue/40 underline-offset-4 hover:decoration-overdue">
                Show overdue
            </a>
        </div>
    @endif

    <section class="mt-6" aria-label="{{ $tabs[$filter] }} tasks">
        @if ($tasks->isEmpty())
            <div class="rounded-xl border border-dashed border-line bg-surface px-6 py-16 text-center">
                <x-icon name="inbox" class="mx-auto size-8 text-ink-faint" :stroke="1.5" />
                <h2 class="mt-4 font-semibold">{{ $emptyMessages[$filter][0] }}</h2>
                <p class="mt-1 text-sm text-ink-soft">{{ $emptyMessages[$filter][1] }}</p>
                @if ($filter === 'all')
                    <a href="{{ route('tasks.create') }}"
                        class="mt-6 inline-flex h-10 items-center gap-2 rounded-lg bg-accent px-4 text-sm font-medium text-white transition-colors hover:bg-accent-strong">
                        <x-icon name="plus" class="size-4" :stroke="2.5" />
                        Add a task
                    </a>
                @endif
            </div>
        @else
            <ul class="divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface shadow-[0_1px_3px_oklch(0.22_0.015_220/0.05)]">
                @foreach ($tasks as $task)
                    @include('tasks._row', ['task' => $task])
                @endforeach
            </ul>
        @endif
    </section>
@endsection
