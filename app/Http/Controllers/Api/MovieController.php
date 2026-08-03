<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // GET /api/movies
    public function index()
    {
        return Movie::all();
    }

    // GET /api/movies/{id}
    public function show($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);

        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'release_year' => $movie->release_year,
            'genres' => $movie->genres->pluck('name')->values()
        ];
    }

    // POST /api/movies
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'release_year' => 'required',
            'genre_ids' => 'array'
        ]);

        $movie = Movie::create([
            'title' => $validated['title'],
            'release_year' => $validated['release_year']
        ]);

        if (isset($validated['genre_ids'])) {
            $movie->genres()->sync($validated['genre_ids']);
        }

        $movie->load('genres');

        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'release_year' => $movie->release_year,
            'genres' => $movie->genres->pluck('name')->values()
        ];
    }

    // PUT /api/movies/{id}
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required',
            'release_year' => 'required',
            'genre_ids' => 'array'
        ]);

        $movie->update([
            'title' => $validated['title'],
            'release_year' => $validated['release_year']
        ]);

        if (isset($validated['genre_ids'])) {
            $movie->genres()->sync($validated['genre_ids']);
        } else {
            $movie->genres()->detach();
        }

        $movie->load('genres');

        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'release_year' => $movie->release_year,
            'genres' => $movie->genres->pluck('name')->values()
        ];
    }

    // DELETE /api/movies/{id}
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        $movie->genres()->detach();
        $movie->delete();

        return response()->json([
            'message' => 'Movie deleted successfully.'
        ]);
    }
}