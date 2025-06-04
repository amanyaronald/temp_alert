<?php

namespace App\Http\Services\Api;

use App\Models\RoomUser;
use Sensy\Scrud\app\Http\Helpers\Model;
use Sensy\Scrud\app\Http\Interfaces\ServiceInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\Room;
use App\Models\User;


class ThresholdApiService implements ServiceInterface
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
            })->with([
                'room.farm.user'
            ]);
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
            $data = $this->_m->with([
                'room.farm.user'
            ])->find($id);
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

            $rooms = Room::all();
            $users = User::LP()->get();


            return [
                'status' => 1,
                'message' => 'Data retrieved for Creation',
                'data' => $data + compact('rooms', 'users')
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

            $rooms = Room::all();
            $users = User::LP()->get();


            $this->_m->updated_by = auth()->id();
            $this->_m->save();
            return [
                'status' => 1,
                'message' => 'Data retrieved for editing',
                'data' => array_merge(
                    ['data' => $data],
                    compact('rooms', 'users')
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


            $this->_m->room_id = $data['room_id'];
            $this->_m->min_temperature = $data['min_temperature'];
            $this->_m->max_temperature = $data['max_temperature'];

            $this->_m->created_by = auth()->id();
            $this->_m->save();

            $sender = $this->updateSensor($data['min_temperature'], $data['max_temperature']);
            if ($sender['status'] == 0) return $sender;

            $notification = $this->sendNotification($data['room_id'],"Room Temperature Set Min:{$data['min_temperature']}, Max:{$data['max_temperature']}");
            if ($notification['status'] == 0) return $notification;

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


            $this->_m->room_id = $data['room_id'];
            $this->_m->min_temperature = $data['min_temperature'];
            $this->_m->max_temperature = $data['max_temperature'];

            $this->_m->updated_by = auth()->id();

            $this->_m->save();

            $sender = $this->updateSensor($data['min_temperature'], $data['max_temperature']);
            if ($sender['status'] == 0) return $sender;

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

    public function updateSensor($min, $max)
    {
        $phone = env('SENSOR_PHONE_NO');
        if (!$phone) return ['status' => 0, 'message' => 'SENSOR_PHONE_NO config missing in env'];
        ## send message sms to sensor to update it
        $request = request();
        $request->merge([
            "to" => $phone,
            "message" => "SET_MAX={$max};SET_MIN={$min}",
        ]);

        return Model::call($request, 'Sms', 'sendMessage');
    }

    public function sendNotification($room,$message)
    {

        #get all users of a room
        $room_users = RoomUser::where('room_id', $room)->get();

        if ($room_users) $room_users->pluck('user_id')->toArray();
        foreach ($room_users as $ru) {
            $request = new Request();
            $request->merge([
                "data" => [
                    'user_id'=>$ru->id,
                    'room_id'=>$room,
                    'message'  =>$message,
                    'notification_type' =>'info',
                ]
            ]);

            $req = Model::call($request, 'Notification', 'store', isApi: true);
            return json_decode($req->getContent(),true);
        }
    }
}
