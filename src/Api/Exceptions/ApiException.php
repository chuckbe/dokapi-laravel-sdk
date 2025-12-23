<?php

declare(strict_types=1);

namespace Dokapi\Api\Exceptions;

use DateTime;
use DateTimeImmutable;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use stdClass;
use Throwable;

final class ApiException extends Exception
{
    /**
     * @var ?string
     */
    protected $field;

    protected string $plainMessage;

    /**
     * @var RequestInterface|null
     */
    protected $request;

    /**
     * @var ResponseInterface|null
     */
    protected $response;

    protected DateTimeImmutable $raisedAt;

    /**
     * @throws ApiException
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?string $field = null,
        ?RequestInterface $request = null,
        ?ResponseInterface $response = null,
        ?Throwable $previous = null,
    ) {
        $this->plainMessage = $message;

        $this->raisedAt = new DateTimeImmutable();

        $formattedRaisedAt = $this->raisedAt->format(DateTime::ISO8601);
        $message = "[{$formattedRaisedAt}] ".$message;

        if (null !== $field) {
            $this->field = (string) $field;
            $message .= ". Field: {$this->field}";
        }

        if (null !== $response) {
            $this->response = $response;
        }

        $this->request = $request;
        if (null !== $request) {
            $requestBody = $request->getBody()->__toString();

            if ('' !== $requestBody) {
                $message .= ". Request body: {$requestBody}";
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws ApiException
     */
    public static function createFromResponse(
        ResponseInterface $response,
        ?RequestInterface $request = null,
        ?Throwable $previous = null,
    ): self {
        $object = static::parseResponseBody($response);

        $field = $object->field ?? null;

        return new self(
            'Error executing API call',
            $response->getStatusCode(),
            $field,
            $request,
            $response,
            $previous
        );
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function hasResponse(): bool
    {
        return null !== $this->response;
    }

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    public function getRaisedAt(): DateTimeImmutable
    {
        return $this->raisedAt;
    }

    /**
     * @throws ApiException
     */
    protected static function parseResponseBody(ResponseInterface $response): stdClass
    {
        $body = (string) $response->getBody();

        $object = @json_decode($body);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new self("Unable to decode Dokapi response: '{$body}'.");
        }

        return $object;
    }

    public function getPlainMessage(): string
    {
        return $this->plainMessage;
    }
}
