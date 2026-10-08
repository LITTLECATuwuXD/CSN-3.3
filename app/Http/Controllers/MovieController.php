<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Movie::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Movie::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Movie::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $movie=Movie::find($id);
        if(!$movie){
            return response()->json(['error' => 'Movie not found'],404);
        }
        $movie->update($request->all());
        return response()->json($movie,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $movie=Movie::find($id);
        if(!$movie){
            return response()->json(['error' => 'Movie not found'],404);
        }
        $movie->delete();
        return response()->json(['message'=>'Movie deleted successfully'],200);
    }
}
