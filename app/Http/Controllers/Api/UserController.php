<?php
namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Validator;

class UserController extends Controller
{
    use HasApiTokens;

    /**
     * Register new user
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'password' => 'required|min:6',
            'phone' => 'required|unique:users|min:10',
            'username' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        \DB::beginTransaction();

        try {

            $dataUser = [];
            $dataUser['username'] = $request->username;
            $dataUser['name'] = $request->name;
            $dataUser['password'] = bcrypt($request->password);
            $dataUser['phone'] = $request->phone;
            $dataUser['username'] = $request->username;

            $createUser = User::create($dataUser);

            $response = [];
            $response['status'] = 200;
            $response['name'] = $dataUser['name'];
            $response['phone'] = $dataUser['phone'];
            $response['username'] = $dataUser['username'];

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }


    /**
     * Login
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        try {

            $authAttempt = Auth::attempt(['username' => $request->username, 'password' => $request->password]);
            if (!$authAttempt)
                throw new \Exception('The credentials you entered did not match our records. Try again?');

            $user = User::where('username', $request->username)->first();
            $token = $user->createToken('access_token')->accessToken;

            $response = [];
            $response['status'] = 200;
            $response['token'] = $token;

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Show detail user by token
     *
     * @return Response
     */
    public function show()
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        try {
            $response = Auth::guard('api')->user();
            $response['status'] = 200;

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
}