<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\Ingredient;

class IngredientController extends Controller
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
     * @param  \App\Http\Requests\StoreIngredientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIngredientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredients
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredients)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ingredient  $ingredients
     * @return \Illuminate\Http\Response
     */
    public function edit(Ingredient $ingredients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateIngredientRequest  $request
     * @param  \App\Models\Ingredient  $ingredients
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingredient  $ingredients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredients)
    {
        //
    }
}
