<?php

namespace Nodeloc\TelegramNotification;

use Flarum\Extend;
use Flarum\Discussion\Event\Started;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),
    (new Extend\Event())
        ->listen(Started::class, Listeners\SendTelegramNotification::class),

    (new Extend\Settings())
        ->serializeToForum('telegramBotToken', 'telegram.bot_token')
        ->serializeToForum('telegramChannelId', 'telegram.channel_id'),
];
