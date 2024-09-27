<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use App\Policies\TaskPolicy;
use App\Policies\TaskStatusPolicy;
use App\Policies\LabelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Task::class => TaskPolicy::class,
        TaskStatus::class => TaskStatusPolicy::class,
        Label::class => LabelPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
