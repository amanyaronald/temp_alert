<?php

namespace App\Http\Services\Api;

use Sensy\Scrud\app\Http\Interfaces\ServiceInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Sensor;
 use App\Models\User;
 

class TemperatureReadingApiService implements ServiceInterface
{
    public $_m;


    public function __construct($_m)
    {
        $model = config('scrud.class.model') . $_m;
        $this->_m = new $model;
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        try {
            $query = $request->input('query');
            $asBuilder = $request->asBuilder;

            $builder = $this->_m->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    
                });
            });
            if ($request->options) {
                $builder = apply_filters($builder, $request->options);
            }

            $data = $asBuilder ? $builder : $builder->get();

            return [
                'status' => 1,
                'message' => 'Fetched successfully',
                'data' => $data
            ];
        } catch (Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function show(Request $request, $id)
    {
        try {
            $data = $this->_m->find($id);
            if (!$data) {
                return [
                    'status' => 0,
                    'message' => 'No Data Found',
                    'data' => $data
                ];
            }

            return [
                'status' => 1,
                'message' => "Retrieved Successfully",
                'data' => $data
            ];

        } catch (\Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Create endpoint: Prepares data for storing.
     *
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            // Validate and prepare data for creation
            $data = $request->data;

            $sensors = Sensor::all();
$users = User::LP()->get();


            return [
                'status' => 1,
                'message' => 'Data retrieved for Creation',
                'data' => $data + compact('sensors','users')
            ];
        } catch (\Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Edit endpoint: Fetches and prepares data for updating.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function edit(Request $request, $id)
    {
        try {
            // Fetch the record to be edited
            $data = $this->_m->find($id);

            if (!$data) {
                return [
                    'status' => 0,
                    'message' => 'No Data Found',
                    'data' => null
                ];
            }

             $sensors = Sensor::all();
$users = User::LP()->get();


            $this->_m->updated_by = auth()->id();
            $this->_m->save();
            return [
                'status' => 1,
                'message' => 'Data retrieved for editing',
                'data' => array_merge(
                    ['data'=>$data],
                    compact('sensors','users')
                )
            ];
        } catch (\Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->data;

            
 $this->_m->sensor_id = $data['sensor_id'];
 $this->_m->temperature_value = $data['temperature_value'];
 $this->_m->recorded_at = $data['recorded_at'];

            $this->_m->created_by = auth()->id();
            $this->_m->save();

            DB::commit();
            return [
                'status' => 1,
                'message' => "Created Successfully",
                'data' => $this->_m
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => ''
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            $this->_m = $this->_m->find($id);

            if (!$this->_m) {
                return [
                    'status' => 0,
                    'message' => 'No Data Found',
                    'data' => []
                ];
            }

            $data = $request->data;

            ##DATA FILLING

            
 $this->_m->sensor_id = $data['sensor_id'];
 $this->_m->temperature_value = $data['temperature_value'];
 $this->_m->recorded_at = $data['recorded_at'];

            $this->_m->updated_by = auth()->id();

            $this->_m->save();
            DB::commit();
            return [
                'status' => 1,
                'message' => "Updated Successfully",
                'data' => $this->_m
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => ''
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request, $id)
    {
        try {
            $data = $this->_m->find($id);

            if (!$data) {
                return [
                    'status' => 0,
                    'message' => 'No Data Found',
                    'data' => null
                ];
            }

            $data->delete();

            return [
                'status' => 1,
                'message' => 'Deleted Successfully',
                'data' => null
            ];
        } catch (\Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @inheritDoc
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $data = $this->_m->withTrashed()->find($id);

            if (!$data) {
                return [
                    'status' => 0,
                    'message' => 'No Data Found',
                    'data' => null
                ];
            }

            $data->forceDelete();

            return [
                'status' => 1,
                'message' => 'Permanently Deleted',
                'data' => null
            ];
        } catch (\Exception $e) {
            log_exception($e);
            return [
                'status' => 0,
                'message' => 'SERVICE ERROR: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
