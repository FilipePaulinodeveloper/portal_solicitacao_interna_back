<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
          
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        

         // 401 - Não autenticado
        $exceptions->render(function (
            AuthenticationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Usuário não autenticado.',
                ], 401);
            }
        });

        // 403 - Sem permissão
        $exceptions->render(function (
            AuthorizationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Você não tem permissão para realizar esta ação.',
                ], 403);
            }
        });

        // 404 - Registro não encontrado

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Recurso não encontrado.',
                ], 404);
            }
        });

        // 404 - Rota não encontrada
        $exceptions->render(function (
            NotFoundHttpException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Recurso não encontrado.',
                ], 404);
            }
        });

          // 405 - Método HTTP não permitido
        $exceptions->render(function (
            MethodNotAllowedHttpException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Método HTTP não permitido.',
                ], 405);
            }
        });


        $exceptions->render(function (
            ValidationException $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Os dados enviados são inválidos.',
                    'erros' => $e->errors(),
                ], 422);
            }
        });
   

        // Erros HTTP genéricos
        $exceptions->render(function (
            HttpExceptionInterface $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Não foi possível processar a requisição.',
                    'error' => $e->getMessage(),
                ], $e->getStatusCode());
            }
        });

        $exceptions->render(function (
            Throwable $e,
            Request $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'mensagem' => 'Ocorreu um erro interno no servidor.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        });

        
     

    })->create();
