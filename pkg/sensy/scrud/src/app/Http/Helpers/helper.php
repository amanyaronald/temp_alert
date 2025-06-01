<?php

use Illuminate\Http\Request;
use \Sensy\Scrud\app\Http\Helpers\ServiceHandler;

if (!function_exists('get_service')) {
    function get_service($class)
    {
        $class = class_basename($class);
        return str_replace(['ApiController','Controller', 'Service'], '', $class);
    }
}


if (!function_exists('get_service_class')) {
    function get_service_class($serviceString,$isApi,$type='service')
    {
        $prefix = config($isApi ? "scrud.class.{$type}.api" : "scrud.class.{$type}.web", '\\App\\Http\\Services\\');
        $service = $prefix . $serviceString . ($isApi ? 'ApiService' : 'Service');
        return $service;
    }
}


if (!function_exists("model")) {

    function model($req, $service, $function = 'index', $action = 'view', $id = null, $asBuilder = false,$isApi = false)
    {
        $caller = service_caller($function,$service,$isApi);
        $service = new ServiceHandler($caller[0]);

        $req->asBuilder = $asBuilder;
        $handler = handler($caller);
        return $service->handle($req, $caller,$id,$isApi);
    }
}

if (!function_exists("apply_filters")) {

    function apply_filters($query, array $filters)
    {
        foreach ($filters as $key => $value) {
            if (!is_null($value)) {
                $query->where($key, $value);
            }
        }
        return $query;
    }

}


if (!function_exists("service_caller")) {
    function service_caller($function, $class, bool $api = false)
    {
        $function = $function;
        $service = $api ? $class . "ApiService" : $class . "Service";
        return array($class, $function, $service, $api);
    }
}

if (!function_exists("handler")) {
    function handler($caller)
    {
        return new ServiceHandler($caller[0]);
    }
}

if (!function_exists('exeption')) {
    /**
     * Throw an exception with optional code and previous.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param string $exceptionClass
     * @return never
     *
     * @throws \Throwable
     */
    function exception(string $message, int $code = 0, \Throwable $previous = null, string $exceptionClass = \Exception::class)
    {
        if (!class_exists($exceptionClass)) {
            $exceptionClass = \Exception::class;
        }

        throw new $exceptionClass($message, $code, $previous);
    }
}


if (!function_exists("log_exception")) {
    function log_exception($e)
    {
        \Log::debug("===========================================================");
        \Log::debug("Exception caught: " . $e->getMessage());
        \Log::debug("Code: " . $e->getCode());
        \Log::debug("File: " . $e->getFile());
        \Log::debug("Line: " . $e->getLine());
        \Log::debug("Trace: " . $e->getTraceAsString());
        \Log::debug("===========================================================");
    }
}
