<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;
use \Tymon\JWTAuth\Exceptions\TokenExpiredException;
use \Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class APIController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 500);
        }

        try {
            JWTAuth::invalidate($token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success'   =>  true,
            'data'      =>  $user
        ], 200);
    }

    public function refresh()
    {   
        $token = JWTAuth::getToken();
        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 500);
        }
        try
        {
            $token = JWTAuth::refresh($token);
        }
        catch(TokenInvalidException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'The token is invalid'
            ], 500);
        }
        catch(TokenExpiredException $e){
            return response()->json([
                'success' => false,
                'message' => 'The token is expired'
            ], 500);
        }
        catch(TokenBlacklistedException $e){
            return response()->json([
                'success' => false,
                'message' => 'The token is blacklisted'
            ], 500);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function status(){
        try 
        {
            $user = JWTAuth::parseToken()->authenticate();
        } 
        catch(TokenInvalidException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'The token is invalid'
            ], 500);
        }
        catch(TokenExpiredException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'The token is expired'
            ], 500);
        }
        catch(TokenBlacklistedException $e)
        {
            return response()->json([
                'success' => false,
                'message' => 'The token is blacklisted'
            ], 500);
        }
        catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getStatusCode()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'status' => 'alive',
        ]);
    }
}
