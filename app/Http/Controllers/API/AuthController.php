<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400) ;
        }
        $loginData = $request->all();

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 400 );
        }
        $user = auth()->user();
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([ 'user' => $user, 'access_token' => $accessToken]);

    }
    public function register(Request $request)
    {
        // Log::debug($request->all());

        $validatedData =Validator::make($request->all(),[
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 400) ;
        }

        $validate = $request->all();

        $validate['password'] = bcrypt($request->password);

        $user = User::create($validate);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([ 'user' => $user, 'access_token' => $accessToken], 200) ;
    }
    public function logout($id)
    {
        $token = User::whereId($id)->tokens;
        if($token){
            $token->revoke();
            return $this->sendResponse(null, 'User is logout');
        } else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'] , 401);
        }

    }

}
