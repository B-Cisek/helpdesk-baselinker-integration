<?php

declare(strict_types=1);

namespace App\Helpdesk\BaseLinker\Client\Exception;

final class ApiException extends BaselinkerException
{
    /**
     * @param array<string, mixed> $response
     */
    public static function fromResponse(array $response): self
    {
        $errorCode = $response['error_code'] ?? 'UNKNOWN';
        $errorMessage = $response['error_message'] ?? 'Unknown API error';

        return new self(
            errorCode: $errorCode,
            errorMessage: sprintf('Baselinker API error [%s]: %s', $errorCode, $errorMessage),
        );
    }
}
