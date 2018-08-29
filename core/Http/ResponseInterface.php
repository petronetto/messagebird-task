<?php

declare(strict_types=1);

namespace Core\Http;

interface ResponseInterface
{
    /**
     * @param  mixed             $body
     * @return ResponseInterface
     */
    public function setBody($body): ResponseInterface;

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @param  array             $name
     * @param  array             $value
     * @return ResponseInterface
     */
    public function setHeaders(string $name, string $value): ResponseInterface;

    /**
     * @param  string            $file
     * @param  string            $args
     * @return ResponseInterface
     */
    public function view(string $file, array $args = [], string $viewsPath = null): ResponseInterface;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @param  int               $statusCode
     * @return ResponseInterface
     */
    public function withStatusCode(int $statusCode): ResponseInterface;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @param  array             $body
     * @return ResponseInterface
     */
    public function withJson(array $body): ResponseInterface;
}
