<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';
    protected $primaryKey = 'id';  // Явное указание первичного ключа
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
