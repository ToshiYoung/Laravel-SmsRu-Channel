<?php

namespace NotificationChannels\SmsRu\Messages;

/**
 *
 */
class SmsRuMessage
{
    /**
     * The message content.
     */
    public string $content;

    /**
     * The phone number the message should be sent from.
     */
    public ?string $from = null;

    /**
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     *
     * @param string $content
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the phone number or sender name the message should be sent from.
     *
     * @param string $from
     * @return $this
     */
    public function from(string $from): static
    {
        $this->from = $from;
        return $this;
    }
}