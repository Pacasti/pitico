<?php

declare(strict_types=1);

namespace database;

final class Response
{
    /**
     * @var array<string, int|string> $response
     */
    protected static array $response = [];

    /**
     * @param  Status  $statusCode Response status code
     * @param  string  $message Response message
     *
     * @return \database\Response
     */
    public static function with(Status $statusCode, string $message): self
    {
        self::$response = [
            'status' => $statusCode->value,
            'message' => $message
        ];

        return new self();
    }

    public function __toString(): string
    {
        return (string) json_encode(self::$response, JSON_UNESCAPED_UNICODE);
    }

}
