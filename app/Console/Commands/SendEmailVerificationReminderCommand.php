<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEmailVerificationReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email verification reminder to users.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))
            ->whereNull('email_verified_at')
            ->each(function (User $user) {
                // Equivalente a $this->notify(new VerifyEmail);
                $user->sendEmailVerificationNotification();
            });
    }
}
