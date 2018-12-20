<?php

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting, so ignore it
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson([
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $statusCode,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack' => $exception->getTraceAsString(),
            ], $statusCode);
    };
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson([
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $statusCode,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack' => $exception->getTraceAsString(),
            ], $statusCode);
    };
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'Application/json')
            ->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
            ->withJson(['error' => true, 'message' => 'Method not Allowed; Method must be one of: ' . implode(', ', $methods)], 405);
    };
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['error' => true, 'message' => 'Page not found']);
    };
};
