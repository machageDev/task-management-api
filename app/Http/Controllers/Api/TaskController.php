<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * 1. Create Task
     * POST /api/tasks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required', 
                'string',
                // Rule: title cannot duplicate a task with the same due_date
                Rule::unique('tasks')->where(function ($query) use ($request) {
                    return $query->where('due_date', $request->due_date);
                }),
            ],
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,done',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * 2. List Tasks
     * GET /api/tasks
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort by priority (high -> medium -> low), then due_date ascending
        $tasks = $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
                       ->orderBy('due_date', 'asc')
                       ->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No tasks found'], 404);
        }

        return response()->json($tasks);
    }

    /**
     * 3. Update Task Status
     * PATCH /api/tasks/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,done'
        ]);

        // Progression Rules: pending -> in_progress -> done
        $allowedMap = [
            'pending' => ['in_progress'],
            'in_progress' => ['done'],
            'done' => [] 
        ];

        if (!isset($allowedMap[$task->status]) || !in_array($request->status, $allowedMap[$task->status])) {
            return response()->json(['error' => 'Invalid status progression'], 403);
        }

        $task->update(['status' => $request->status]);
        return response()->json($task);
    }

    /**
     * 4. Delete Task
     * DELETE /api/tasks/{id}
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->status !== 'done') {
            return response()->json(['error' => 'Only completed tasks can be deleted'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

 
    public function report(Request $request)
    {
    // Use the date from the URL, or default to today's date
    $date = $request->query('date', date('Y-m-d'));

    $priorities = ['high', 'medium', 'low'];
    $statuses = ['pending', 'in_progress', 'done'];
    $summary = [];

    foreach ($priorities as $priority) {
        foreach ($statuses as $status) {
            // Count tasks matching this specific date, priority, and status
            $summary[$priority][$status] = Task::where('due_date', $date)
                ->where('priority', $priority)
                ->where('status', $status)
                ->count();
        }
    }

    return response()->json([
        'report_date' => $date,
        'data' => $summary
    ]);
    }

}