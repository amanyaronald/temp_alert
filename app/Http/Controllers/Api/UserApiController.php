<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Sensy\Scrud\app\Http\Helpers\Model;


use Sensy\Scrud\Traits\FileUploadTrait;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserApiController extends Controller
{
    use FileUploadTrait;

    public $paginate = 10;
    public $title;

    public function __construct()
    {
        $this->title = ucfirst('User');
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
            # Validate request data

            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'role' => 'required|in:farmer,manager',
                'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
    }


    public function edit(Request $request, $id)
    {
        return Model::call($request, get_service(__class__), __function__, id: $id, isApi: true);
    }

    public function update(Request $request, $id)
    {
        try {
            #Validate request data
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'nullable|min:8',
                'password_confirmation' => 'nullable|same:password',
                'role' => 'required',
                'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

    public function login(Request $request)
    {
        try {
            # Validate request data

            $data = $request->validate([
                'email' => 'required',
                'password' => 'required|min:8',
            ]);

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

    public function updatePwd(Request $request)
    {
        try {
            # Validate request data

            $data = $request->validate([
                'old_password' => 'required',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|min:8|same:password',
            ]);

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

}
