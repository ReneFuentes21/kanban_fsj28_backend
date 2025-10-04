<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CardController extends Controller
{
    public function index()
    {
        return Card::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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
            'name' => 'sometimes|required|string|max:255'
        ]);

        $card = Card::findOrFail($id);
        $card->update($request->all());

        return response()->json($card);
    }

    public function updateId(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'board_id' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $card = Card::find($id);
        $card->update($request->all());
        return response()->json(["message" => "ID updated successfully"], 200);
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        return response()->json(["message" => "Card deleted successfully"], 204);
    }
}
