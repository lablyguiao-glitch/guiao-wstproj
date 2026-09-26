<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public const PENDING = 'Pending';
    public const COMPLETED = 'Completed';

    public const STATUSES = [self::PENDING, self::COMPLETED];

    protected $fillable = [
        'task_name',
        'description',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function isCompleted(): bool
    {
        return $this->status === self::COMPLETED;
    }

    /**
     * A task is overdue when it is still pending and its due date has passed.
     */
    public function isOverdue(): bool
    {
        return ! $this->isCompleted()
            && $this->due_date !== null
            && $this->due_date->lt(today());
    }

    public function isDueToday(): bool
    {
        return ! $this->isCompleted()
            && $this->due_date !== null
            && $this->due_date->isToday();
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', self::PENDING);
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', self::COMPLETED);
    }

    public function scopeOverdue(Builder $query): void
    {
        $query->where('status', self::PENDING)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today());
    }
}
