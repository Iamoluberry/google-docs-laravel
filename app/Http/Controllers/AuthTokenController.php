<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthTokenRequest;
use App\Http\Requests\UpdateAuthTokenRequest;
use App\Models\AuthToken;

class AuthTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthTokenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthToken $authToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthToken $authToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthTokenRequest $request, AuthToken $authToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthToken $authToken)
    {
        //
    }
}
