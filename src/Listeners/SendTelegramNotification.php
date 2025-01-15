<?php

namespace Nodeloc\TelegramNotification\Listeners;

use Flarum\Discussion\Event\Started;
use Nodeloc\TelegramNotification\Jobs\SendTelegramNotificationJob;
use Illuminate\Contracts\Queue\Queue;

class SendTelegramNotification
{
    protected $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function handle(Started $event)
    {
        $discussion = $event->discussion;

        $this->queue->push(new SendTelegramNotificationJob(
            $discussion->title,
            $discussion->user->username,
            $discussion->id
        ));
    }
}
