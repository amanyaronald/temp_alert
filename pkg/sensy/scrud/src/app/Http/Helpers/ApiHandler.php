<?php

namespace Sensy\Scrud\app\Http\Helpers;

use Illuminate\Http\Request;

class ApiHandler
{

    protected $service;

    private $serviceClass;
    protected $controller;

    /**
     * Dynamically resolve the service class based on the model name.
     */
    public function __construct()
    {
        // Get the model name from the request
        $this->serviceClass = request()->get('serviceClass');

        if ($this->serviceClass) {
            $this->controller = config('scrud.class.controller.api') . $this->serviceClass . 'ApiController';

            $this->controller = rtrim($this->controller, '\\'); // Remove trailing backslash from namespace
            $this->controller = ltrim($this->controller, '\\'); // Remove start backslash from namespace

            $this->service = request()->get('service');

            $this->controller = new $this->controller();
        } else {
            abort(400, "INVALID REQUEST STRUCTURE - Missing [serviceClass]");
        }
    }

    /**
     * Retrieve all records (GET /api/{model}).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
           return $this->controller->{__FUNCTION__}($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error fetching records: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Retrieve a single record by ID (GET /api/{model}/{id}).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $service , $id,)
    {
        try {
            return $this->controller->{__FUNCTION__}($request,$id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error fetching record: ' . $e->getMessage(),
                'data' => [],
            ], 404);
        }
    }

    /**
     * Retrive resources to create record (GET /api/{model}/create).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $service)
    {
        try {
            return $this->controller->{__FUNCTION__}($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error preparing for Create: ' . $e->getMessage(),
                'data' => [],
            ], 422);
        }
    }

    /**
     * Create a new record (POST /api/{model}).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            return $this->controller->{__FUNCTION__}($request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error creating record: ' . $e->getMessage(),
                'data' => [],
            ], 422);
        }
    }


    /**
     * Retrive resources to create record (GET /api/{model}/create).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $service,$id)
    {
        try {
            return $this->controller->{__FUNCTION__}($request,$id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error preparing for Edit: ' . $e->getMessage(),
                'data' => [],
            ], 422);
        }
    }

    /**
     * Update an existing record (PUT/PATCH /api/{model}/{id}).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,$service, $id)
    {
        try {
            return $this->controller->{__FUNCTION__}($request,$id);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error updating record: ' . $e->getMessage(),
                'data' => [],
            ], 422);
        }
    }

    /**
     * Soft delete a record (DELETE /api/{model}/{id}).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request,$service, $id)
    {
        try {
            return $this->controller->{__FUNCTION__}($request,$id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error deleting record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Permanently delete a record (DELETE /api/{model}/{id}/force).
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete(Request $request, $service,$id)
    {
        try {
            return $this->controller->{__FUNCTION__}($request,$id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error permanently deleting record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

}
