<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Board; // ¡Importación esencial para verificar la existencia del Tablero!

/**
 * @OA\Schema(
 * schema="Card",
 * title="Card",
 * description="Modelo de datos de una Tarjeta (Card), que actúa como columna dentro de un Tablero (Board).",
 * @OA\Property(property="id", type="integer", description="ID único de la tarjeta/columna"),
 * @OA\Property(property="board_id", type="integer", description="ID del tablero al que pertenece la tarjeta"),
 * @OA\Property(property="title", type="string", description="Título de la tarjeta (ej. Por Hacer, En Progreso)"),
 * @OA\Property(property="order", type="integer", description="Posición de la tarjeta dentro del tablero (para ordenar las columnas)"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de última actualización")
 * )
 */

class CardController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/boards/{boardId}/cards",
     * tags={"Tarjetas"},
     * summary="Obtiene todas las tarjetas (columnas) de un tablero específico",
     * @OA\Parameter(
     * name="boardId",
     * in="path",
     * required=true,
     * description="ID del tablero",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de tarjetas obtenida exitosamente",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Card")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Tablero no encontrado"
     * )
     * )
     */
    public function index(int $boardId)
    {
        // Corregido: Si el tablero no existe, se devuelve 404 (esto resolvía el problema inicial).
        if (!Board::where('id', $boardId)->exists()) {
            return response()->json([
                'message' => 'Tablero no encontrado.'
            ], 404);
        }

        // Obtener las tarjetas del tablero, forzando el orden por 'order' ascendente.
        $cards = Card::where('board_id', $boardId)
                     //->orderBy( 'asc')
                     ->get();
        //$cards = Board::all();
        return response()->json($cards, 200);
    }

    /**
     * @OA\Post(
     * path="/api/v1/boards/{boardId}/cards",
     * tags={"Tarjetas"},
     * summary="Crea una nueva tarjeta (columna) para el tablero especificado",
     * @OA\Parameter(
     * name="boardId",
     * in="path",
     * required=true,
     * description="ID del tablero al que se añadirá la tarjeta",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la tarjeta a crear",
     * @OA\JsonContent(
     * required={"title"},
     * @OA\Property(property="title", type="string", example="En Progreso"),
     * @OA\Property(property="order", type="integer", example="2", description="Opcional. Si se omite, se calcula automáticamente.")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Tarjeta creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Card")
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
    public function store(Request $request, int $boardId)
    {
        // 1. Verificar si el tablero existe.
        if (!Board::where('id', $boardId)->exists()) {
             return response()->json([
                'message' => 'Tablero no encontrado.'
            ], 404);
        }

        // 2. Validación: 'order' es 'nullable' (opcional) y 'title' es obligatorio.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            //'order' => 'nullable|integer',  Preguntar si es necesario
        ]);
        
        // 3. Lógica para calcular el 'order' (posición) si no fue proporcionado. Preguntar
       // $maxOrder = Card::where('board_id', $boardId)->max('order');
        //$newOrder = $validatedData['order'] ?? ($maxOrder === null ? 1 : $maxOrder + 1);   Preguntar si es necesario

        // 4. Crear la tarjeta.
        $card = Card::create([
            'board_id' => $boardId,
            'name' => $validatedData['title'],
            
        ]);

        return response()->json($card->toArray())
                     ->setStatusCode(201);
       // return response()->json($card, 201);
    }

    /**
     * @OA\Get(
     * path="/api/v1/cards/{id}",
     * tags={"Tarjetas"},
     * summary="Obtiene una tarjeta específica por ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarjeta",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarjeta obtenida exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Card")
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarjeta no encontrada"
     * )
     * )
     */
    public function show($id)
    {
        $card = Card::findOrFail($id); 
        return response()->json($card);
    }

    /**
     * @OA\Put(
     * path="/api/v1/cards/{id}",
     * tags={"Tarjetas"},
     * summary="Actualiza el título o la posición de una tarjeta (columna)",
     * description="Se usa para renombrar la columna o para reordenar las columnas. Opcionalmente, permite moverla de tablero.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarjeta a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * description="Datos para actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="title", type="string", example="Listo para QA"),
     * @OA\Property(property="order", type="integer", example="3"),
     * @OA\Property(property="board_id", type="integer", example="5", description="Opcional. Permite mover la tarjeta a un tablero diferente.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarjeta actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Card")
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarjeta no encontrada"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validación corregida: Usamos 'title', y 'board_id' debe existir.
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'order' => 'sometimes|required|integer', 
            // Corregido: board_id debe ser entero y debe existir en la tabla boards.
            'board_id' => 'sometimes|required|integer|exists:boards,id', 
        ]);

        $card = Card::findOrFail($id);
        $card->update($validatedData);

        return response()->json($card);
    }

    /**
     * @OA\Patch(
     * path="/api/v1/cards/{id}/move",
     * tags={"Tarjetas"},
     * summary="Mueve una tarjeta a otro tablero",
     * description="Actualiza solo el board_id de una tarjeta para moverla a otro tablero. Utiliza el método PATCH.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarjeta a mover",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Nuevo ID del tablero de destino",
     * @OA\JsonContent(
     * required={"board_id"},
     * @OA\Property(property="board_id", type="integer", example="10", description="El ID del nuevo tablero al que pertenece la tarjeta.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="ID de tablero actualizado exitosamente"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarjeta no encontrada"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function updateId(Request $request, $id)
    {
        // Validación corregida: board_id es obligatorio, entero y debe existir.
        $validator = Validator::make($request->all(), [
            'board_id' => 'required|integer|exists:boards,id' 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $card = Card::findOrFail($id); 
        $card->update($request->only('board_id')); 
        
        return response()->json(["message" => "ID de tablero actualizado exitosamente"], 200);

        //echo "Hola";
    }

    /**
     * @OA\Delete(
     * path="/api/v1/cards/{id}",
     * tags={"Tarjetas"},
     * summary="Elimina una tarjeta (columna) por su ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarjeta a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Tarjeta eliminada exitosamente (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarjeta no encontrada"
     * )
     * )
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        // Corregido: 204 No Content debe devolver un cuerpo vacío.
        return response()->json(null, 204); 
    }
}
