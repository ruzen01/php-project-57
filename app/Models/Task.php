<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status_id
 * @property int $created_by_id
 * @property int $assigned_to_id
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Label[] $labels
 */

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';  // Указываем явно первичный ключ
    public $incrementing = true;
    protected $keyType = 'int';

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

    public function labels(): BelongsToMany
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
