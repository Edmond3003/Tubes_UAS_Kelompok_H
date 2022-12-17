<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //find departemen by ID
        $user= User::findOrFail($user->id);
        return new UserResource(
            true,
            'List Data User',
            $user
        );
        if ($user) {

            //update departemen
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Updated',
                'data'    => $user
            ], 200);
        }

        //data departemen not found
        return response()->json([
            'success' => false,
            'message' => 'User Not Found',
        ], 404);
    }


}
