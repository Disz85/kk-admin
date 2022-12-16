<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCategoryableRequest;
use App\Http\Requests\UpdateCategoryableRequest;
use App\Models\Categoryable;

class CategoryableController extends Controller
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
     * @param  \App\Http\Requests\StoreCategoryableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryableRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoryable  $categoryable
     * @return \Illuminate\Http\Response
     */
    public function show(Categoryable $categoryable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoryable  $categoryable
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoryable $categoryable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryableRequest  $request
     * @param  \App\Models\Categoryable  $categoryable
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryableRequest $request, Categoryable $categoryable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoryable  $categoryable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoryable $categoryable)
    {
        //
    }
}
