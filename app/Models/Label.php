<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Label extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected $primaryKey = 'id';  // Явно указываем первичный ключ

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
