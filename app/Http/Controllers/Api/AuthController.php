<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
    /**
     * Register User
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required|min:3|max:30",
                    "email" => "required|email|unique:users",
                    "password" => [
                        'required', "max:20", Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
                    ],
                ],
                [
                    "password.regex" => "Password must be combination of number, letter, and special characters !$#%@ ."
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => []
                ]);
            }

            // $user = User::create($request->all());
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);
            return response()->json([
                "status" => true,
                "message" => "User create successfully.",
                "data" => $user,
                "token" => $user->createToken("API TOKEN")->plainTextToken
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
                "data" => []
            ]);
        }
    }

    /**
     * Login user
     * 
     * @return Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "email" => "required|email",
                    "password" => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => []
                ]);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    "status" => false,
                    "message" => "User login failed.",
                    "data" => []
                ]);
            }
            $user = User::where("email", $request->email)->first();
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully.",
                "data" => ["token" => $user->createToken("API TOKEN")->plainTextToken]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
                "data" => []
            ]);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        if ($user->count() > 0) {
            return response()->json([
                "status" => true,
                "message" => "All users data",
                "data" => $user
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "No user found.",
            "data" => []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "email" => "required|email",
                    "password" => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => []
                ]);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    "status" => false,
                    "message" => "User login failed.",
                    "data" => []
                ]);
            }
            $user = User::where("email", $request->email)->first();
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully.",
                "data" => ["token" => $user->createToken("API TOKEN")->plainTextToken]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
                "data" => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            "status" => true,
            "message" => "Retrieved single user data",
            "data" => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required|min:3|max:30",
                    "email" => "required|unique:users,email,$user->id,id",
                    "password" => [
                        'required', "max:20", Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
                    ],
                ],
                [
                    "password.regex" => "Password must be combination of number, letter, and special characters !$#%@ ."
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "message" => $validator->errors(),
                    "data" => []
                ]);
            }

            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);
            return response()->json([
                "status" => true,
                "message" => "User updated successfully.",
                "data" => $user,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
                "data" => []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            "status" => true,
            "message" => "User deleted successfully.",
            "data" => []
        ]);
    }
}
