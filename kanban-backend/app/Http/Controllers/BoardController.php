<?php

namespace App\Http\Controllers;

use App\Models\Board;


use Illuminate\Http\Request;

class BoardController extends Controller
{
    
    public function index()
    {
        return Board::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $board = Board::create($request->all());

        return response()->json($board, 201);
    }

    public function show($id)
    {
        $board = Board::findOrFail($id);
        return response()->json($board);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $board = Board::findOrFail($id);
        $board->update($request->all());

        return response()->json($board, 200);
    }

    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return response()->json(null, 204);
    }
}
