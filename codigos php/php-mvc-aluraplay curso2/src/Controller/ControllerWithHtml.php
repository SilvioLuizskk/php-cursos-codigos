<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

abstract class ControllerWithHtml
{
    private const TEMPLATE_PATH = __DIR__ . '/../../views/';
    
    protected function renderTemplate(string $templateName, array $context = []): string
    {
        extract($context);
     
        ob_start();
        require_once self::TEMPLATE_PATH . $templateName . '.php';
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