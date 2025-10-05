<?php

namespace App\Http\Controllers;


use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 * schema="Task",
 * title="Task",
 * description="Modelo de datos de una Tarea, el elemento individual dentro de una Tarjeta (Card).",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="ID único de la tarea"
 * ),
 * @OA\Property(
 * property="card_id",
 * type="integer",
 * description="ID de la tarjeta (columna) a la que pertenece la tarea"
 * ),
 * @OA\Property(
 * property="taskName",
 * type="string",
 * description="Nombre o título de la tarea"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * description="Descripción detallada de la tarea"
 * ),
 * @OA\Property(
 * property="startDate",
 * type="string",
 * format="date",
 * description="Fecha de inicio de la tarea"
 * ),
 * @OA\Property(
 * property="endDate",
 * type="string",
 * format="date",
 * description="Fecha de vencimiento o fin de la tarea"
 * ),
 * @OA\Property(
 * property="allocator",
 * type="string",
 * description="Usuario que asignó la tarea"
 * ),
 * @OA\Property(
 * property="employee",
 * type="string",
 * nullable=true,
 * description="Usuario al que está asignada la tarea"
 * ),
 * @OA\Property(
 * property="priority",
 * type="string",
 * enum={"Alta", "Media", "Baja"},
 * description="Prioridad de la tarea"
 * ),
 * @OA\Property(
 * property="progress",
 * type="string",
 * description="Estado del progreso (ej. Pendiente, En curso, Bloqueado)"
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
class TaskController extends Controller
{
    
    /**
     * @OA\Get(
     * path="/api/v1/tasks",
     * tags={"Tareas"},
     * summary="Obtiene todas las tareas",
     * description="Recupera una lista de todas las tareas existentes.",
     * @OA\Response(
     * response=200,
     * description="Lista de tareas obtenida exitosamente",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Task")
     * )
     * )
     * )
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * @OA\Get(
     * path="/api/v1/tasks/{id}",
     * tags={"Tareas"},
     * summary="Obtiene una tarea específica por ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarea a buscar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarea obtenida exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarea no encontrada"
     * )
     * )
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

     /**
     * @OA\Post(
     * path="/api/v1/tasks",
     * tags={"Tareas"},
     * summary="Crea una nueva tarea",
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la tarea a crear",
     * @OA\JsonContent(
     * required={"taskName", "description", "startDate", "endDate", "allocator", "priority", "progress", "card_id"},
     * @OA\Property(property="taskName", type="string", example="Implementar OAuth"),
     * @OA\Property(property="description", type="string", example="Configurar la autenticación con Google y Facebook."),
     * @OA\Property(property="startDate", type="string", format="date", example="2025-10-01"),
     * @OA\Property(property="endDate", type="string", format="date", example="2025-10-15"),
     * @OA\Property(property="allocator", type="string", example="Jane Doe"),
     * @OA\Property(property="employee", type="string", example="John Smith", nullable=true),
     * @OA\Property(property="priority", type="string", enum={"Alta", "Media", "Baja"}, example="Alta"),
     * @OA\Property(property="progress", type="string", example="Pendiente"),
     * @OA\Property(property="card_id", type="integer", example="5")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Tarea creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/Task")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'card_id' => 'required|integer|exists:cards,id',
            'taskName' => 'required|string',
            'description' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'allocator' => 'required|string',
            'employee' => 'sometimes|string',
            'priority' => 'required|string',
            'progress' => 'required|integer', 
            
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],422);
        }
        $request = Task::create($request->all());
        return response()->json($request, 201);
    }

     /**
     * @OA\Put(
     * path="/api/v1/tasks/{id}",
     * tags={"Tareas"},
     * summary="Actualiza los detalles de una tarea",
     * description="Permite actualizar el nombre, descripción, prioridad y/o fecha de finalización de una tarea.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarea a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * description="Campos de la tarea a actualizar",
     * @OA\JsonContent(
     * @OA\Property(property="taskName", type="string", example="Implementar Autenticación"),
     * @OA\Property(property="description", type="string", example="Descripción actualizada."),
     * @OA\Property(property="priority", type="string", enum={"Alta", "Media", "Baja"}, example="Media"),
     * @OA\Property(property="endDate", type="string", format="date", example="2025-10-20", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Tarea actualizada exitosamente"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarea no encontrada"
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'taskName' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|string',   
            'endDate' => 'nullable|date|after_or_equal:today'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $task = Task::find($id);
        $task->update($request->all());
        return response()->json(["message" => "Task updated successfully"], 200);
    }

    /**
     * @OA\Patch(
     * path="/api/v1/tasks/update-card-id/{id}",
     * tags={"Tareas"},
     * summary="Mueve una tarea a una nueva tarjeta (columna)",
     * description="Método utilizado para cambiar la tarjeta (columna) a la que pertenece la tarea (ej. de 'Por Hacer' a 'En Progreso').",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarea a mover",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Nuevo ID de la tarjeta de destino",
     * @OA\JsonContent(
     * required={"card_id"},
     * @OA\Property(property="card_id", type="integer", example="8", description="El ID de la tarjeta a la que se moverá la tarea.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="ID de tarjeta actualizado exitosamente"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarea no encontrada"
     * )
     * )
     */
    
    public function updateId(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'card_id' => 'required|integer'  //Preguntar si sera string o integer
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $task = Task::find($id);
        $task->update($request->all());
        return response()->json(["message" => "Task updated successfully"], 200);
    }

    /**
     * @OA\Get(
     * path="/api/v1/tasks/daysLeft/{id}",
     * tags={"Tareas"},
     * summary="Calcula los días restantes hasta la fecha de vencimiento",
     * description="Devuelve el número de días que faltan para el endDate de la tarea.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarea",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Días restantes calculados",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="taskName", type="string", example="Implementar OAuth"),
     * @OA\Property(property="endDate", type="string", format="date", example="2025-10-15"),
     * @OA\Property(property="remaining_days", type="integer", example=10, description="Días restantes. Negativo si la fecha ya pasó."),
     * @OA\Property(property="detail", type="string", example="Queda tiempo o La tarea ya termino")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarea no encontrada"
     * )
     * )
     */
    public function daysLeft($id){
        $task = Task::find($id);
        $today = Carbon::today();
        $dueDate = Carbon::parse($task->endDate);
        $diffDays = $today->diffInDays($dueDate, false);

        return response()->json([
            'id' => $task->id,
            'taskName' => $task->taskName,
            'endDate' => $task->endDate,
            'remaining_days' => $diffDays,
            'detail' => $diffDays > 0 ? "Queda tiempo" : "La tarea ya termino"],200);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/tasks/{id}",
     * tags={"Tareas"},
     * summary="Elimina una tarea por su ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la tarea a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Tarea eliminada exitosamente (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Tarea no encontrada"
     * )
     * )
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
