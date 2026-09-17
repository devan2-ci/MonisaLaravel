<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function signUp(Request $request) {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'phone'    => [
                    'required',
                    'string',
                    'regex:/^0[0-9]{9,14}$/',
                    'unique:users,phone'
                ],
                'username' => [
                    'required',
                    'string',
                    'min:6',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
                    'unique:users,username'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
                ],
            ];

            $messages = [
                'name.required' => 'Name is required',
                //message email
                'email.required'    => 'Email is required',
                'email.email'       => 'Invalid email format',
                'email.unique'      => 'Email already registered',
                //message phone
                'phone.required'    => 'Phone number is required',
                'phone.regex'       => 'Phone must start with 0 and contain 10–15 digits',
                'phone.unique'      => 'Phone number already registered',

                //message username
                'username.required' => 'Username is required',
                'username.min'      => 'Username must be at least 6 characters',
                'username.regex'    => 'Username must contain letters and numbers',
                'username.unique'   => 'Username already registered',
                //message password
                'password.required'  => 'Password is required',
                'password.min'       => 'Password must be at least 8 characters',
                'password.confirmed' => 'Password confirmation does not match',
                'password.regex'     => 'Password must contain letters and numbers'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            // ❌ Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // ✅ Normalisasi phone
            $phone = preg_replace('/[^0-9]/', '', $request->phone);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
                'username' => $request->username,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sign up success'
            ]);
        } catch (Exception $e) {
            $log = implode(PHP_EOL, [
                'Error : SIGN UP FAILED',
                'Message : ' . $e->getMessage(),
                'File    : ' . $e->getFile(),
                'Line    : ' . $e->getLine(),
                'Trace   :',
                $e->getTraceAsString(),
            ]);
            Log::error($log);

            return response()->json([
                'success' => false,
                'message' => 'Sign up failed'
            ], 400);
        }
    }

    function signIn(Request $request) {
        try {
            $rules = [
                'username' => [
                    'required',
                    'string',
                    'min:6',
                    // 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    // 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
                ],
            ];

            $messages = [
                // Username
                'username.required' => 'Username is required',
                'username.min'      => 'Username must be at least 6 characters',
                // 'username.regex'    => 'Username must contain letters and numbers',

                // Password
                'password.required' => 'Password is required',
                'password.min'      => 'Password must be at least 8 characters',
                // 'password.regex'    => 'Password must contain letters and numbers',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            // ❌ Validation failed
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // ❌ Authentication failed
            if (!Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username or password is incorrect',
                ], 401);
            }

            // ✅ Auth success
            $user = Auth::user();

            // (Optional) revoke old tokens
            // $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Sign in success',
                'token'   => $token,
                'user'    => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->getRoleNames()->first(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error(implode(PHP_EOL, [
                'Error : SIGN IN FAILED',
                'Message : ' . $e->getMessage(),
                'File    : ' . $e->getFile(),
                'Line    : ' . $e->getLine(),
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Sign in failed',
            ], 400);
        }
    }

    public function signout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function profile(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak valid atau sudah logout.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile success',
            'data'    => $request->user()
        ]);
    }
}
