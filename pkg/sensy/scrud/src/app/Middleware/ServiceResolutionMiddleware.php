<?php

namespace Sensy\Scrud\app\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ServiceResolutionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract the model name from the request
        $modelName = $this->getModelNameFromRequest($request);

        if ($modelName) {
            // Dynamically resolve the service class for the model
            $serviceClass = $this->resolveServiceClass($modelName);

            if ($serviceClass) {
                // Attach the resolved service instance to the request;
                $request->attributes->set('service', $serviceClass);


                $request->attributes->set('serviceClass', $modelName);
                $request->attributes->set('serviceController', config('scrud.class.api.controller') . $modelName . 'ApiController');
            } else {
                $m = "Api Service for model [$modelName] not found.";
                log_exception(new \Exception($m));
                return response()->json([
                    'status' => 0,
                    'message' => $m,
                    'data' => [],
                ], 404);
            }
        }

        // Proceed to the next middleware or controller
        return $next($request);
    }

    /**
     * Extract the model name from the request.
     *
     * @param Request $request
     * @return string|null
     */
    protected function getModelNameFromRequest(Request $request): ?string
    {
        // Example: Extract model name from route parameters (e.g., /api/tests)
        $route = $request->route();
        if ($route && isset($route->parameters['service'])) {
            return Str::singular(Str::studly($route->parameters['service']));
        }

        $path = $request->path();
        #replace api/
        $path = preg_replace('/^api\//', '', $path);

        // Fallback: Extract model name from the URL path (e.g., /api/tests)
        $pathSegments = explode('/', trim($path, '/'));
        if (!empty($pathSegments)) {
            return Str::singular(Str::studly($pathSegments[0]));
        }

        return null;
    }

    /**
     * Resolve the service class for the given model name.
     *
     * @param string $modelName
     * @return string|null
     */
    protected function resolveServiceClass(string $modelName): ?string
    {
       $serviceClassName =  get_service_class($modelName,true);

        // Check if the service class exists
        if (class_exists($serviceClassName)) {
            return $serviceClassName;
        }

        return null;
    }

    /**
     * Generate a standardized response.
     *
     * @param array $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function response(array $data, int $statusCode = 200)
    {
        // Determine if the request expects JSON (API) or HTML (Web)
        if (request()->expectsJson()) {
            return response()->json($data, $statusCode);
        }

        // For Web requests, return a view or redirect
        return response()->view('response', $data, $statusCode);
    }
}
