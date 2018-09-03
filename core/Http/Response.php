<?php

declare(strict_types=1);

namespace Core\Http;

use Core\Exceptions\NotFoundException;

class Response implements ResponseInterface
{
    /** @var mixed */
    protected $body;

    /** @var int */
    protected $statusCode = 200;

    /** @var array */
    protected $headers = [];

    /**
     * @param  mixed             $body
     * @return ResponseInterface
     */
    public function setBody($body): ResponseInterface
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
     * @param  array             $name
     * @param  array             $value
     * @return ResponseInterface
     */
    public function setHeaders(string $name, string $value): ResponseInterface
    {
        $this->headers[] = [$name, $value];

        return $this;
    }

    /**
     * @param string $file
     * @param array $args
     * @param string $viewsPath
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function view(string $file, array $args = [], string $viewsPath = null): ResponseInterface
    {
        if (!$viewsPath) {
            $viewsPath = base_path('app/Views/');
        }

        $viewFile  = sprintf('%s/%s.php', $viewsPath, $file);

        if (!file_exists($viewFile)) {
            throw new NotFoundException;
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
     * @param  int               $statusCode
     * @return ResponseInterface
     */
    public function withStatusCode(int $statusCode): ResponseInterface
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
     * @param  array             $body
     * @return ResponseInterface
     */
    public function withJson(array $body): ResponseInterface
    {
        $this->setHeaders('Content-Type', 'application/json');

        $this->setBody(json_encode($body));

        return $this;
    }

    /**
     * @param  string            $path
     * @return ResponseInterface
     */
    public function redirect(string $path): ResponseInterface
    {
        $this->setHeaders('location', base_url($path));

        return $this;
    }
}
