<?php

namespace App\Http\Controllers;


use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //
    public function index()
    {
        return Task::all();
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'taskName' => 'required|string',
            'description' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'allocator' => 'required|string',
            'employee' => 'sometimes|string',
            'priority' => 'required|string',
            'progress' => 'required|string',
            'card_id' => 'required|exists:cards,id',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()],422);
        }
        $request = Task::create($request->all());
        return response()->json($request, 201);
    }

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

    public function updateId(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'card_id' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $task = Task::find($id);
        $task->update($request->all());
        return response()->json(["message" => "Task updated successfully"], 200);
    }

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

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
