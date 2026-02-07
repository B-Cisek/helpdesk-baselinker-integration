<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\BaseLinker\Client\Exception;

abstract class BaselinkerException extends \RuntimeException
{
    public function __construct(
        public readonly string $errorCode,
        public readonly string $errorMessage,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($errorMessage, $code, $previous);
    }
}
