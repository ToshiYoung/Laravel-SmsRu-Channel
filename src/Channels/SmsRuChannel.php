<?php

namespace NotificationChannels\SmsRu\Channels;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsRu\Exceptions\ResponseException;
use NotificationChannels\SmsRu\Messages\SmsRuMessage;
use NotificationChannels\SmsRu\SmsRuApi;

/**
 *
 */
class SmsRuChannel
{
    /**
     * @var SmsRuApi
     */
    protected SmsRuApi $client;

    /**
     * @param SmsRuApi $client
     */
    public function __construct(SmsRuApi $client)
    {
        $this->client = $client;
    }

    /**
     * @throws ResponseException
     * @throws GuzzleException
     */
    public function send($notifiable, Notification $notification): ?array
    {
        if (!$to = $notifiable->routeNotificationFor('sms_ru', $notification)) {
            return null;
        }

        $message = $notification->{'toSmsRu'}($notifiable);

        if (is_string($message)) {
            $message = new SmsRuMessage($message);
        }

        $payload = [
            'to' => $to,
            'msg' => trim($message->content)
        ];

        if ($message->from) {
            $payload['from'] = $message->from;
        }

        return $this->client->send($payload);
    }
}