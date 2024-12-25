<?php

namespace App\Console\Commands;

use App\Mail\PendingTasksReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPendingTaskEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command allows you to send users an email about pending tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::with('incompleteTasks')->get();
        foreach ($users as $user) {
            Mail::to($user->email)->queue(new PendingTasksReminderMail($user));
        }
    }
}
