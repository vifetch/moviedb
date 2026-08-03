<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenreController extends Controller
{
    // GET /api/genres
    public function index()
    {
        return Genre::all();
    }

    // GET /api/genres/{id}
    public function show($id)
    {
        $genre = Genre::with('movies')->findOrFail($id);

        return [
            'id' => $genre->id,
            'name' => $genre->name,
            'movies' => $genre->movies->map(function ($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title
                ];
            })->values()
        ];
    }
}