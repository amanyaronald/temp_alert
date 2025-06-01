<?php

namespace App\Http\Services\Api;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Sensy\Scrud\app\Http\Interfaces\ServiceInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserApiService implements ServiceInterface
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
                    $subQuery->where('name', 'LIKE', '%' . $query . '%');
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

            $users = User::LP()->get();


            return [
                'status' => 1,
                'message' => 'Data retrieved for Creation',
                'data' => $data + compact('users')
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

            $users = User::LP()->get();


            $this->_m->updated_by = auth()->id();
            $this->_m->save();
            return [
                'status' => 1,
                'message' => 'Data retrieved for editing',
                'data' => array_merge(
                    ['data' => $data],
                    compact('users')
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

            $this->_m->name = $data['name'];
            $this->_m->email = $data['email'];
            $this->_m->email_verified_at = $data['email_verified_at'] ?? now();
            $this->_m->role = $data['role'];
            $this->_m->password = $data['password'];
//            $this->_m->created_by = auth()?->id();

//            dd($this->_m,$data['password']);
            $this->_m->save();

            // Generate API token using Sanctum
            $token = $this->_m->createToken('api_token')->plainTextToken;

            DB::commit();
            return [
                'status' => 1,
                'message' => "Created Successfully",
                'data' => [
                    'user' => $this->_m,
                    'token' => $token
                ]
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


            $this->_m->name = $data['name'];
            $this->_m->email = $data['email'];
            $this->_m->email_verified_at = $data['email_verified_at'];
            $this->_m->role = $data['role'];
            $this->_m->password = $data['password'];

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

    public function login(Request $request)
    {
        try {
            $data = $request->data;

            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $remember = $data['remember'] ?? false;

            $throttleKey = Str::transliterate(Str::lower($email) . '|' . $request->ip());

            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                event(new Lockout($request));
                $seconds = RateLimiter::availableIn($throttleKey);

                return [
                    'status' => 0,
                    'message' => __('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                    'data' => []
                ];
            }

            if (!Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                RateLimiter::hit($throttleKey);

                return [
                    'status' => 0,
                    'message' => __('auth.failed'),
                    'data' => []
                ];
            }

            RateLimiter::clear($throttleKey);
            Session::regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Generate Sanctum token
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'status' => 1,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
//                    'redirect_to' => route('dashboard'),
                ]
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

    public function updatePwd(Request $request)
    {
        try {
            $data = $request->data;


            /** @var \App\Models\User $user */
            $user = Auth::user();
            if(!$user) return [
                'status' => 0,
                'message' => 'User not Authenticated.',
                'data' => []
            ];

            // Confirm old password
            if (!Hash::check($data['old_password'], $user->password)) {
                return [
                    'status' => 0,
                    'message' => 'The current password is incorrect.',
                    'data' => []
                ];
            }

            // Update new password
            $user->password = $data['password'];
            $user->save();

            return [
                'status' => 1,
                'message' => 'Password updated successfully.',
                'data' => []
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

}
