<?php

namespace App\Http\Controllers;

use App\Models\Board;


use Illuminate\Http\Request;

/**
 * @OA\Schema(
 * schema="Board",
 * title="Board",
 * description="Modelo de datos de un Tablero (Board)",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="ID único del tablero"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre del tablero (ej. Proyecto Alfa)"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Fecha de creación"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Fecha de última actualización"
 * )
 * )
 */
class BoardController extends Controller
{
      /**
     * @OA\Get(
     * path="/api/v1/boards",
     * tags={"Tableros"},
     * summary="Obtiene todos los tableros",
     * @OA\Response(
     * response=200,
     * description="Lista de tableros obtenida exitosamente",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Board")
     * )
     * )
     * )
     */
    public function index()
    {
        return Board::all();
    }

     /**
     * @OA\Post(
     * path="/api/v1/boards",
     * tags={"Tableros"},
     * summary="Crea un nuevo tablero",
     * @OA\RequestBody(
     * required=true,
     * description="Datos del tablero a crear",
     * @OA\JsonContent(
     * required={"name"},
     * @OA\Property(property="name", type="string", example="Tablero de Marketing"),
     * required={"numCards"},
     * @OA\Property(property="numCards", type="integer", example=5)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Tablero creado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Board")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'numCards' => 'required|integer'
        ]);

        $board = Board::create($request->all());

        return response()->json($board, 201);
    }

    /**
     * @OA\Get(
     * path="/api/v1/boards/{id}",
     * tags={"Tableros"},
     * summary="Muestra un tablero específico",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del tablero a mostrar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tablero encontrado",
     * @OA\JsonContent(ref="#/components/schemas/Board")
     * ),
     * @OA\Response(
     * response=404,
     * description="Tablero no encontrado"
     * )
     * )
     */
    public function show($id)
    {
        $board = Board::findOrFail($id);
        return response()->json($board);
    }

    /**
     * @OA\Put(
     * path="/api/v1/boards/{id}",
     * tags={"Tableros"},
     * summary="Actualiza un tablero existente",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del tablero a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos del tablero para actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Tablero de Marketing V2")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Tablero actualizado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Board")
     * ),
     * @OA\Response(
     * response=404,
     * description="Tablero no encontrado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $board = Board::findOrFail($id);
        $board->update($request->all());

        return response()->json($board, 200);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/boards/{id}",
     * tags={"Tableros"},
     * summary="Elimina un tablero por su ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del tablero a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Tablero eliminado exitosamente (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tablero no encontrado"
     * )
     * )
     */
    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return response()->json(null, 204);
    }
}
