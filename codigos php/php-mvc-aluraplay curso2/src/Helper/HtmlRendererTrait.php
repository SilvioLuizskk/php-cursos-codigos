<?php

declare(strict_types=1);

namespace Alura\Mvc\Helper;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait HtmlRendererTrait
{
    
    private function renderTemplate(string $templateName, array $context = []): string
    {
        $templatePath = __DIR__ . '/../../views/';
        extract($context);

        ob_start();
        require_once $templatePath . $templateName . '.php';
        $content = ob_get_clean();
        return $content;
    }

    protected function createHtmlResponse(string $html, int $status = 200): ResponseInterface
    {
        return new Response($status, ['Content-Type' => 'text/html; charset=UTF-8'], $html);
    }

    protected function createRedirectResponse(string $location, int $status = 302): ResponseInterface
    {
        return new Response($status, ['Location' => $location]);
    }

    protected function createJsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }
}