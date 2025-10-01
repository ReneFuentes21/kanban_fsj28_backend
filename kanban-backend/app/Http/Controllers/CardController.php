<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return Card::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
        ]);

        $card = Card::create($request->all());

        return response()->json($card, 201);
    }

    public function show($id)
    {
        $card = Card::findOrFail($id);
        return response()->json($card);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'board_id' => 'sometimes|required|exists:boards,id',
        ]);

        $card = Card::findOrFail($id);
        $card->update($request->all());

        return response()->json($card);
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        return response()->json(null, 204);
    }
}