<?php

namespace LaravelSmsRu\Exceptions;

use DomainException;
use Exception;

/**
 *
 */
class ResponseException extends Exception
{
    /**
     * Thrown when sms.ru return error
     *
     * @param  DomainException  $exception
     * @return static
     */
    public static function respondedWithAnError(DomainException $exception): self
    {
        return new static(
            "sms.ru return error {$exception->getCode()}: {$exception->getMessage()}'",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * @param Exception $exception
     * @return static
     */
    public static function connectionError(Exception $exception): self
    {
        return new static(
            "sms.ru connection failed. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }
}