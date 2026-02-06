<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Exception;

final class TransportException extends BaselinkerException
{
    public static function fromThrowable(\Throwable $exception): self
    {
        return new self(
            errorCode: 'TRANSPORT_ERROR',
            errorMessage: sprintf('Baselinker transport error: %s', $exception->getMessage()),
            code: $exception->getCode(),
            previous: $exception,
        );
    }
}
