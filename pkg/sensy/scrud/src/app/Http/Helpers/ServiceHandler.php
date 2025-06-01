<?php

namespace Sensy\Scrud\app\Http\Helpers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class ServiceHandler
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, $caller, $id = null, $json = false)
    {
        #check if there is data key in request
        if (!isset($request->data)) {
            $response = [
                'status' => 0,
                'message' => 'Invalid Request Structure - Missing [data]',
                'data' => []
            ];
        } elseif (!$this->service) {
            $response = [
                'status' => 0,
                'message' => 'Invalid Request Structure - Missing [service]',
                'data' => []
            ];
        } elseif (!isset($caller[1])) {
            $response = [
                'status' => 0,
                'message' => "Invalid Request Structure - Missing [caller['endpoint']]",
                'data' => []
            ];
        } else {
            $isApi = $caller[3];

            $service = get_service_class($this->service,$isApi);

            $caller['service'] = $service;

            $s_caller = new $service($this->service);

            if (is_null($id)) {
                $response = $s_caller->{$caller[1]}($request);
            } else {
                $response = $s_caller->{$caller[1]}($request, $id);
            }

        }

        return $this->response($response, $caller, $caller[3]);
    }

    public function response($data, $caller, $jsonResponse)
    {
        $expectedKeys = ['status', 'message', 'data'];

        // Check if all required keys are present in $data
        $isValid = is_array($data) && !array_diff($expectedKeys, array_keys($data));

        // Handle invalid response structure
        if (!$isValid) {
            $data = [
                'status' => 0,
                'message' => "INVALID SERVICE RESPONSE STRUCTURE - {$caller['service']}",
                'data' => []
            ];
        }

        // Return JSON if requested
        if ($jsonResponse ?? false) {
            return response()->json($data);
        }

        return $data;
    }



}
