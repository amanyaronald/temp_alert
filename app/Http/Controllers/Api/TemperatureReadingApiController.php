<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnotherTable;

use Sensy\Scrud\app\Http\Helpers\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Sensor;
 use App\Models\User;
 

class TemperatureReadingApiController extends Controller
{
    public $title;
    public $paginate = 10;

    public $serviceHandle;
    public $caller;

    public $options = ['parner_id' => null];

    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return Model::call($request, get_service(__class__), __function__, isApi: true);
    }

    public function create(Request $request)
    {
        return Model::call($request, get_service(__class__), __function__, isApi: true);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
'sensor_id' => 'required',
'temperature_value' => 'required|decimal',
'recorded_at' => 'required',
]);
            // $data['file'] = $this->uploadFile($request, 'file', ['required']);
            $request->data = $data;

            return Model::call($request, get_service(__class__), __function__, isApi: true);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'data' => [],
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            log_exception($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error creating record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
        } catch (\Exception $e) {
            log_exception($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error fetching record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
'sensor_id' => 'required',
'temperature_value' => 'required|decimal',
'recorded_at' => 'required',
]);
            // $data['file'] = $this->uploadFile($request, 'file', ['required']);
            $request->data = $data;

            return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'data' => [],
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            log_exception($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error updating record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
        } catch (\Exception $e) {
            log_exception($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error deleting record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
        } catch (\Exception $e) {
            log_exception($e);
            return response()->json([
                'status' => 0,
                'message' => 'Error force deleting record: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    // Uncomment and use when you need custom logic
    // public function custom(Request $request)
    // {
    //     try {
    //         return Model::call($request, get_service(__class__), __function__, isApi: true);
    //     } catch (\Exception $e) {
    //         log_exception($e);
    //         return response()->json([
    //             'status' => 0,
    //             'message' => 'Error: ' . $e->getMessage(),
    //             'data' => [],
    //         ], 500);
    //     }
    // }
}
