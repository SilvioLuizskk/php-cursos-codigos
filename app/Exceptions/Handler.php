<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Default reporting (logs, external services, etc.)
        });

        // Tratamento global para exceções de domínio: ProductNotFoundException
        $this->renderable(function (ProductNotFoundException $e, Request $request) {
            // Retorna JSON se a requisição espera JSON, caso contrário usa abort(404)
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $e->getMessage() ?: 'Produto não encontrado.'
                ], 404);
            }

            // Para requisições web, delegamos ao abort para usar as views de erro padrão
            abort(404, $e->getMessage());
        });
    }
}
