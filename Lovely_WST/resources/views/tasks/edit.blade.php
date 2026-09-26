@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('tasks.index') }}"
            class="-ml-1 inline-flex items-center gap-1.5 rounded-md px-1 text-sm font-medium text-ink-soft transition-colors hover:text-ink">
            <x-icon name="arrow-left" class="size-4" />
            All tasks
        </a>

        <h1 class="mt-6 text-3xl font-semibold tracking-tight">Edit task</h1>
        <p class="mt-2 text-ink-soft">Update the details, or change its status.</p>

        <form method="POST" action="{{ route('tasks.update', $task) }}" novalidate
            class="mt-8 rounded-xl border border-line bg-surface p-5 shadow-[0_1px_3px_oklch(0.22_0.015_220/0.05)] sm:p-8">
            @csrf
            @method('PUT')
            @include('tasks._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>
@endsection
