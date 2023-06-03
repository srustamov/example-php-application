<?php

namespace App\Support\Http;

class Response
{
    public function __construct(
        protected mixed $content = '',
        protected int   $status = 200,
        protected array $headers = []
    )
    {
        if (is_array($content)) {
            $this->setContent(json_encode($content));
            $this->setHeader('Content-Type', 'application/json');
        } else if ($content instanceof self) {
            $this->setContent($content->getContent());
            $this->setStatus($content->status);
            $this->headers = array_merge($this->headers, $content->headers);
        } else {
            $this->setContent((string)$content);
        }
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->content;

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}