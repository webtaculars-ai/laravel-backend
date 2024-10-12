<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;

class TimesheetController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
        ]);

        $timesheet = Timesheet::create($request->only([
            'user_id',
            'project_id',
            'task_name',
            'date',
            'hours',
        ]));

        return response()->json($timesheet, 201);
    }

    public function show($id)
    {
        $timesheet = Timesheet::with(['user', 'project'])->find($id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        return response()->json($timesheet);
    }

    public function index(Request $request)
    {
        $query = Timesheet::query();

        if ($request->has('task_name')) {
            $query->where('task_name', $request->task_name);
        }
        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $timesheets = $query->with(['user', 'project'])->get();

        return response()->json($timesheets);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:timesheets,id',
            'user_id' => 'sometimes|exists:users,id',
            'project_id' => 'sometimes|exists:projects,id',
            'task_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'hours' => 'sometimes|numeric|min:0',
        ]);

        $timesheet = Timesheet::find($request->id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        $timesheet->fill($request->only([
            'user_id',
            'project_id',
            'task_name',
            'date',
            'hours',
        ]));

        $timesheet->save();

        return response()->json($timesheet->load(['user', 'project']));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:timesheets,id',
        ]);

        $timesheet = Timesheet::find($request->id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        $timesheet->delete();

        return response()->json(['message' => 'Timesheet deleted successfully']);
    }
}
