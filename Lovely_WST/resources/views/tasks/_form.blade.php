@php
    $inputClasses = 'block w-full rounded-lg border bg-surface px-3.5 py-2.5 text-ink placeholder:text-ink-faint transition-colors focus:outline-none focus:ring-3';
    $status = old('status', $task->status ?? \App\Models\Task::PENDING);
@endphp

<div class="space-y-6">
    <div>
        <label for="task_name" class="text-sm font-medium">Task name</label>
        <input type="text" id="task_name" name="task_name" required maxlength="255" autofocus
            value="{{ old('task_name', $task->task_name) }}"
            placeholder="e.g. Finish chapter 3 reading"
            @error('task_name') aria-invalid="true" aria-describedby="task_name-error" @enderror
            @class([$inputClasses, 'mt-2', 'border-overdue focus:ring-overdue/15' => $errors->has('task_name'), 'border-line focus:border-accent focus:ring-accent/15' => ! $errors->has('task_name')])>
        @error('task_name')
            <p id="task_name-error" class="mt-2 text-sm text-overdue">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="text-sm font-medium">
            Description <span class="font-normal text-ink-faint">(optional)</span>
        </label>
        <textarea id="description" name="description" rows="4" maxlength="2000"
            placeholder="Any details you want to remember"
            @class([$inputClasses, 'mt-2 resize-y', 'border-overdue focus:ring-overdue/15' => $errors->has('description'), 'border-line focus:border-accent focus:ring-accent/15' => ! $errors->has('description')])>{{ old('description', $task->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-overdue">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="due_date" class="block text-sm leading-5 font-medium">
                Due date <span class="font-normal text-ink-faint">(optional)</span>
            </label>
            <input type="date" id="due_date" name="due_date"
                value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                @class([$inputClasses, 'mt-2 h-11 py-0 tabular-nums', 'border-overdue focus:ring-overdue/15' => $errors->has('due_date'), 'border-line focus:border-accent focus:ring-accent/15' => ! $errors->has('due_date')])>
            @error('due_date')
                <p class="mt-2 text-sm text-overdue">{{ $message }}</p>
            @enderror
        </div>

        <div role="radiogroup" aria-labelledby="status-label">
            <p id="status-label" class="text-sm leading-5 font-medium">Status</p>
            <div class="mt-2 grid h-11 grid-cols-2 gap-1 rounded-lg border border-line bg-canvas p-1">
                @foreach (\App\Models\Task::STATUSES as $option)
                    <label class="flex cursor-pointer">
                        <input type="radio" name="status" value="{{ $option }}" class="peer sr-only" @checked($status === $option)>
                        <span class="flex flex-1 items-center justify-center rounded-md text-sm font-medium text-ink-soft transition-colors peer-checked:bg-surface peer-checked:text-ink peer-checked:shadow-[0_1px_2px_oklch(0.22_0.015_220/0.12)] peer-focus-visible:outline-2 peer-focus-visible:outline-accent">
                            {{ $option }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('status')
                <p class="mt-2 text-sm text-overdue">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="mt-10 flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
    <a href="{{ route('tasks.index') }}"
        class="inline-flex h-11 items-center justify-center rounded-lg px-5 text-sm font-medium text-ink-soft transition-colors hover:bg-canvas hover:text-ink">
        Cancel
    </a>
    <button type="submit"
        class="inline-flex h-11 cursor-pointer items-center justify-center rounded-lg bg-accent px-5 text-sm font-medium text-white transition-colors hover:bg-accent-strong">
        {{ $submitLabel }}
    </button>
</div>
