<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewsletterNotification;
use Illuminate\Console\Command;

class SendNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter
                            {emails?*} : Emails to send newsletter
                            {--s|schedule : If the command should be scheduled}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newsletter to all subscribers.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $emails = $this->argument('emails');
        $schedule = $this->option('schedule');
        $builder = User::query();

        if ($emails) {
            $builder->whereIn('email', $emails);
        }

        $count = $builder->count();

        if ($count) {
            $this->output->progressStart($count);

            if ($schedule || $this->confirm("Are you sure you want to send the newsletter to $count users?")) {
                $builder
                    ->whereNotNull('email_verified_at')
                    ->each(function (User $user): void {
                        $user->notify(new NewsletterNotification());
                        $this->output->progressAdvance();
                    });

                $this->info("Newsletter sent to $count users.");
                $this->output->progressFinish();
            }
        }

        $this->info('No emails sent.');
    }
}
