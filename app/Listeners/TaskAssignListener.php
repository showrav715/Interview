<?php

namespace App\Listeners;

use App\Events\TaskAssignEvent;
use App\Mail\AssignTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TaskAssignListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskAssignEvent $task): void
    {
        $user = $task->task->user;
        Mail::to($user->email)->later(now()->addSeconds(5), new AssignTask($task->task));
    }
}
