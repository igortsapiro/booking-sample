<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class BadRequestHttpException extends Exception implements HttpExceptionInterface
{
    protected $code = Response::HTTP_BAD_REQUEST;

    private array $headers;

    public function __construct(
        $message = '',
        $code = 0,
        Throwable $previous = null,
        array $headers = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->headers = $headers;
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
