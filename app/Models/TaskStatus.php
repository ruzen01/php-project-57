<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class TaskStatus extends Model
{
    use HasFactory;
    // Разрешаем массовое присвоение для поля 'name'
    protected $fillable = ['name'];
    // Определяем связь один ко многим с моделью Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
