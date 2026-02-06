<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Client\Exception;

final class RateLimitException extends BaselinkerException
{
    public static function create(): self
    {
        return new self(
            errorCode: 'RATE_LIMIT',
            errorMessage: 'Baselinker API rate limit exceeded (HTTP 429)',
            code: 429,
        );
    }
}
