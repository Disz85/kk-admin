<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShelfRequest;
use App\Http\Requests\UpdateShelfRequest;
use App\Models\Shelf;

class ShelfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param StoreShelfRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShelfRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Shelf $shelves
     * @return \Illuminate\Http\Response
     */
    public function show(Shelf $shelves)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Shelf $shelves
     * @return \Illuminate\Http\Response
     */
    public function edit(Shelf $shelves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateShelfRequest $request
     * @param Shelf $shelves
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShelfRequest $request, Shelf $shelves)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shelf $shelves
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shelf $shelves)
    {
        //
    }
}
