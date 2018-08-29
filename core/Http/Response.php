<?php

declare(strict_types=1);

namespace Core\Http;

use Core\Exceptions\NotFoundException;

class Response
{
    /** @var mixed */
    protected $body;

    /** @var int */
    protected $statusCode = 200;

    /** @var array */
    protected $headers = [];

    /**
     * @param  mixed    $body
     * @return Response
     */
    public function setBody($body): Response
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param  array    $name
     * @param  array    $value
     * @return Response
     */
    public function setHeaders(string $name, string $value): Response
    {
        $this->headers[] = [$name, $value];

        return $this;
    }

    /**
     * @param  string   $file
     * @param  string   $args
     * @return Response
     */
    public function view(string $file, array $args = [], string $viewsPath = null): Response
    {
        if (!$viewsPath) {
            $viewsPath = base_path('app/Views/');
        }

        $viewFile  = sprintf('%s/%s.php', $viewsPath, $file);

        if (!file_exists($viewFile)) {
            throw new NotFoundException();
        }

        ob_start();
        extract($args, EXTR_SKIP);
        include($viewFile);
        $res = ob_get_contents();
        ob_end_clean();

        $this->setBody($res);

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param  int      $statusCode
     * @return Response
     */
    public function withStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param  array    $body
     * @return Response
     */
    public function withJson(array $body): Response
    {
        $this->setHeaders('Content-Type', 'application/json');

        $this->setBody(json_encode($body));

        return $this;
    }
}
