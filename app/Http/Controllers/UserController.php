<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function index(){
        return UserResource::collection(User::Paginate(10));
    }

    public function store(StoreUserRequest $request){

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role_id' => 3,
        ]);

        event(new Registered($user));

        return response()->json(['User created!'], 201);
    }

    public function show(User $user){
        if ($user->is(Auth::user()) || Auth::user()->roles->slug === 'admin') {
            return response()->json($user);
        }
        return response()->json(["message" => "You are not authorized to access this user as it isn't yours!"], 403);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request['name'] ?? $user->name,
            'email' => $request['email'] ?? $user->email]);
        return response()->json(['message' => 'User updated!'], 200);
    }

    public function destroy(User $user){
        $user = Auth::user();

        $user->delete();
        return response()->json(
            ['message' => 'User deleted!'], 200
        );
    }

    public function updateUserRole(User $user, UpdateRoleRequest $request)
    {
        if (Auth::user()->roles->slug === 'admin') {
            $newUser = User::find($request['user_id']);
            $newRole = Role::find($request['role_id']);

            if (!$newUser || !$newRole) {
                return response()->json([
                    'message' => 'User or role not found'
                ], 404);
            }

            $newUser->update([
                'role_id' => $newRole->id
            ]);

            return response()->json([
                'message' => 'User role updated!'
            ], 200);
        } else{
            return response()->json(['Cant assign role'], 403);
        }
    }

}
