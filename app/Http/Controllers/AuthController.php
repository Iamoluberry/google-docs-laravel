<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteAuthToken;
use App\Models\AuthToken;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $token = bin2hex(random_bytes(4));

        AuthToken::create([
            'token' => $token,
            'user_id' => $user->id,
            'expiry_date' => now()->addMinutes(30),
        ]);

        $mapClaims = array("token" => $token);
        json_encode($mapClaims);

        $jwtToken = JWTAuth::claims($mapClaims)->fromUser($user);

        //delete the token in AuthToken table after 30 mins
        DeleteAuthToken::dispatch($user->id)->delay(now()->addMinutes(30));

        return response()->json([
            'message' => 'Logged in Successfully',
            'access_token' => $jwtToken,
            'expire_time' => "30 minutes",
        ], response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();


        if ($user){
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $payload = JWTAuth::setToken($token)->getPayload();
            $authToken = $payload->get('token');

            if (!AuthToken::where('token', $authToken)->exists()) {
                auth()->logout();
                return response()->json(['error' => 'Expired token'], 401);
            }

            AuthToken::where('token', $authToken)->delete();

            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        } else{
            return response()->json(['error' => "Unauthenticated"], 401);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function verificationNotice()
    {
        $user = Auth::user();

        if(!$user->email_verified_at){

            return response()->json([
                'message' => 'Click the verification link sent to your email address!'
            ]);
        }

        return response()->json([
            'message' => 'Email already verified'
        ], 200);
    }

    protected function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = Auth::user();

        $role_id = Role::where('slug', 'user')->first()->id;

        $user->update([
            'role_id' => $role_id,
        ]);

        return response()->json([
            'message' => 'Email verified successfully',
        ], 200);
    }

    protected function requestNewVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if(!$user->email_verified_at){
            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }

        return response()->json([
            'message' => 'Email already verified'
        ], 200);
    }
}
