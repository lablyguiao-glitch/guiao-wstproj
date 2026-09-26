<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tasks') · {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased">
    <header class="border-b border-line bg-surface">
        <div class="mx-auto flex h-16 max-w-4xl items-center justify-between gap-4 px-4 sm:px-6">
            <a href="{{ route('tasks.index') }}" class="flex min-w-0 items-center gap-2.5 rounded-md font-semibold tracking-tight">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-accent text-white">
                    <x-icon name="check" class="size-4.5" :stroke="2.75" />
                </span>
                <span class="truncate">{{ config('app.name') }}</span>
            </a>

            @unless (request()->routeIs('tasks.create'))
                <a href="{{ route('tasks.create') }}"
                    class="inline-flex h-10 shrink-0 items-center gap-2 rounded-lg bg-ink px-4 text-sm font-medium text-white shadow-[0_1px_2px_oklch(0.22_0.015_220/0.2)] transition-colors hover:bg-accent-strong">
                    <x-icon name="plus" class="size-4" :stroke="2.5" />
                    New task
                </a>
            @endunless
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 pt-8 pb-20 sm:px-6 sm:pt-12">
        @yield('content')
    </main>

    @if (session('success'))
        <div role="status"
            class="flash fixed inset-x-4 bottom-6 z-10 mx-auto flex max-w-sm items-center gap-3 rounded-xl bg-ink px-4 py-3 text-sm text-white shadow-[0_8px_24px_-6px_oklch(0.22_0.015_220/0.35)]">
            <x-icon name="check" class="size-4 text-accent-wash" :stroke="2.5" />
            <span>{{ session('success') }}</span>
        </div>
    @endif
</body>
</html>
