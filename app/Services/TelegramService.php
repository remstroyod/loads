<?php
namespace App\Services;

use Telegram\Bot\Api;

class TelegramService
{

    protected $telegram;
    protected $chatId;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->chatId = env('TELEGRAM_CHAT_ID');

    }

    public function sendMessage($message): bool|\Telegram\Bot\Objects\Message
    {

        if(empty($this->chatId)) return false;

        return $this->telegram->sendMessage([
            'chat_id'   => $this->chatId,
            'text'          => $message,
            'parse_mode'    => 'Markdown'
        ]);
    }


}
