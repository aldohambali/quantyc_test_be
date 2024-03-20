<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

    }

    public function index()
    {

        $data = User::all();
        return response()->json([
            'success' => true,
            'data'    => $data
        ], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'id'        => 'required',
            'role'      => 'required',
        ];

        $prepareData = [
            'role' => $request->role
        ];

        if(isset($request->username)){
            $rules['username']='required|unique:users';
            $prepareData['username']=$request->username;
        }
        if(isset($request->email)){
            $rules['email']='required|email|unique:users';
            $prepareData['email']=$request->email;
        }
        if(isset($request->name)){
            $rules['name']='required';
            $prepareData['name']=$request->name;
        }
        if(isset($request->password)){
            $rules['password']='required|min:8';
            $prepareData['password']=bcrypt($request->password);
        }
        Log::info('rules :: '.json_encode($rules));
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all() 
            ], 200);
        }
        Log::info('prepareData :: '.json_encode($prepareData));
        $data = User::where('id', $request->id)
        ->update($prepareData);

        if($data>0){
            return response()->json([
                'success' => true,
                'data'    => $data
                ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => array('Update user tidak berhasil')
            ], 200);
        }


    }

    public function delete(Request $request)
    {
        Log::info('delete :: '.json_encode($request));
        $validator = Validator::make($request->all(), [
            'id'      => 'required',
            'password'  => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all() 
            ], 200);
        }

        $user = User::find($request->id);

        if(isset($user)){
            $currentUser = auth()->guard('api')->user();
            $currentUserData = User::find($currentUser->id);
            $checkPass = Hash::check($request->password, $currentUser->password);
            if($checkPass==true){
                $deleted = User::where('id', $request->id)->delete();
                $data = 'delete';
                return response()->json([
                    'success' => true,
                    'data'    => $data
                ], 200);
            } else {
                Log::info('pass not match');
                return response()->json([
                    'success' => false,
                    'message' => array('Password tidak sesuai.') 
                ], 200);
            }

        } else {
            Log::info('id not found');
            return response()->json([
                'success' => false,
                'message' => array('User tidak ditemukan.')
            ], 200);
        }

    }
}
