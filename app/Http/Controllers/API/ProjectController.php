<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:not_started,in_progress,completed',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::create($request->only([
            'name',
            'department',
            'start_date',
            'end_date',
            'status',
        ]));

        if ($request->has('user_ids')) {
            $project->users()->attach($request->user_ids);
        }

        return response()->json($project->load('users'), 201);
    }

    public function show($id)
    {
        $project = Project::with(['users', 'timesheets'])->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->has('name')) {
            $query->where('name', $request->name);
        }
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('end_date', $request->end_date);
        }

        $projects = $query->with(['users', 'timesheets'])->get();

        return response()->json($projects);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'name' => 'sometimes|string|max:255',
            'department' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'status' => 'sometimes|in:not_started,in_progress,completed',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::find($request->id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->fill($request->only([
            'name',
            'department',
            'start_date',
            'end_date',
            'status',
        ]));

        $project->save();

        if ($request->has('user_ids')) {
            $project->users()->sync($request->user_ids);
        }

        return response()->json($project->load('users'));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
