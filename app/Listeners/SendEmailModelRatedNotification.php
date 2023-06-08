<?php

namespace App\Listeners;

use App\Events\ModelRated;
use App\Models\Product;
use App\Notifications\ModelRatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailModelRatedNotification implements ShouldQueue
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
    public function handle(ModelRated $event): void
    {
        $rateable = $event->rateable;

        if ($rateable instanceof Product) {
            $notification = new ModelRatedNotification($event->qualifier->name, $rateable->name, $event->score);

            $rateable->createdBy->notify($notification);
        }
    }
}
