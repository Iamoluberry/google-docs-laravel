<?php

namespace App\Repositories;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

class CustomTokenRepository implements TokenRepositoryInterface
{
    protected $table = 'auth_tokens';

    public function create($user)
    {
        // Generate a unique token
        $token = Str::random(60);

        // Store the token in the database
        DB::table($this->table)->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $token;
    }

    public function exists($user, $token)
    {
        // Check if the token exists for the given user
        return DB::table($this->table)
            ->where('email', $user->email)
            ->where('token', $token)
            ->exists();
    }

    public function delete($token)
    {
        // Delete the token
        DB::table($this->table)->where('token', $token)->delete();
    }

    public function deleteExpired()
    {
        // Delete expired tokens
        DB::table($this->table)
            ->where('created_at', '<', now()->subMinutes(config('auth.passwords.users.expire')))
            ->delete();
    }

    public function recentlyCreatedToken(CanResetPasswordContract $user)
    {
        // TODO: Implement recentlyCreatedToken() method.
    }
}
