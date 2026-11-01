<?php
namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'synopsis' => 'required|string',
            'year'     => 'required|integer',
            'cover'    => 'required', // string URL o archivo
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path; // ej: covers/abc.jpg
        }

        $movie = Movie::create($data);
        return response()->json($movie, 201);
    }

    public function show(string $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
        return response()->json($movie);
    }

    public function update(Request $request, string $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }

        $data = $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'synopsis' => 'sometimes|required|string',
            'year'     => 'sometimes|required|integer',
            'cover'    => 'sometimes|required',
        ]);

        if ($request->hasFile('cover')) {
            // Borrar portada anterior si era archivo local
            if ($movie->cover && !filter_var($movie->cover, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($movie->cover);
            }
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path;
        }

        $movie->update($data);
        return response()->json($movie, 200);
    }

    public function destroy(string $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
        // Borrar archivo si era local
        if ($movie->cover && !filter_var($movie->cover, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($movie->cover);
        }
        $movie->delete();
        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
}
