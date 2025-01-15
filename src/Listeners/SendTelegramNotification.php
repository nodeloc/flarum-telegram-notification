<?php

namespace Nodeloc\TelegramNotification\Listeners;

use Flarum\Discussion\Event\Started;
use Nodeloc\TelegramNotification\Jobs\SendTelegramNotificationJob;
use Illuminate\Contracts\Queue\Queue;
use Flarum\Settings\SettingsRepositoryInterface;

class SendTelegramNotification
{
    protected $queue;
    protected $settings;

    public function __construct(Queue $queue, SettingsRepositoryInterface $settings)
    {
        $this->queue = $queue;
        $this->settings = $settings;
    }

    protected function shouldExclude($discussion)
    {
        $excludedTags = $this->settings->get('telegram.excluded_tags');
        if (!$excludedTags) {
            return false;
        }

        // 将排除的标签ID转换为数组
        $excludedTagIds = array_map('trim', explode(',', $excludedTags));

        // 获取讨论的标签ID
        $discussionTagIds = $discussion->tags->pluck('id')->toArray();

        // 检查是否有交集
        return !empty(array_intersect($excludedTagIds, $discussionTagIds));
    }

    public function handle(Started $event)
    {
        $discussion = $event->discussion;

        // 检查是否应该排除这个主题
        if ($this->shouldExclude($discussion)) {
            return;
        }

        $job = new SendTelegramNotificationJob(
            $discussion->title,
            $discussion->id
        );

        $this->queue->push($job);

    }
}
