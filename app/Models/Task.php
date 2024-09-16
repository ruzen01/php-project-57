<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'description', 'status_id', 'created_by_id', 'assigned_to_id'];

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }

    public function scopeLabel(Builder $query, int $labelId): Builder
    {
        return $query->whereHas('labels', function (Builder $q) use ($labelId) {
            $q->where('labels.id', $labelId);
        });
    }
}
