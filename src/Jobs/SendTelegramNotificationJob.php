<?php

namespace Nodeloc\TelegramNotification\Jobs;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Flarum\Foundation\Application;

class SendTelegramNotificationJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $discussionTitle;
    protected $authorName;
    protected $discussionId;

    public function __construct(string $discussionTitle, string $authorName, int $discussionId)
    {
        $this->discussionTitle = $discussionTitle;
        $this->authorName = $authorName;
        $this->discussionId = $discussionId;
    }

    public function handle(SettingsRepositoryInterface $settings, Application $app)
    {
        $botToken = $settings->get('telegram.bot_token');
        $channelId = $settings->get('telegram.channel_id');

        if (!$botToken || !$channelId) {
            return;
        }

        $message = sprintf(
            "📢 新主题: %s\n作者: %s\n链接: %s",
            $this->discussionTitle,
            $this->authorName,
            $app->url() . '/d/' . $this->discussionId
        );

        $client = new Client();

        try {
            $response = $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'json' => [
                    'chat_id' => $channelId,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ]
            ]);
        } catch (\Exception $e) {
            app('log')->error('Telegram notification failed: ' . $e->getMessage());
        }
    }
}
