<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\HouseholdTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of household tasks.
     */
    public function index(Request $request)
    {
        $tasks = HouseholdTask::where('customer_id', Auth::id())
            ->orderBy('reminder_date', 'asc')
            ->orderBy('reminder_time', 'asc')
            ->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'tasks' => $tasks]);
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created household task via AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'reminder_time' => 'required',
            'priority' => 'required|in:low,medium,high',
        ]);

        $task = HouseholdTask::create([
            'customer_id' => Auth::id(),
            'task_title' => $validated['task_title'],
            'description' => $validated['description'] ?? null,
            'reminder_date' => $validated['reminder_date'],
            'reminder_time' => $validated['reminder_time'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        // Log Activity
        ActivityLog::record(Auth::id(), 'Task Created', "Created household task: {$task->task_title}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Household task created successfully!',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Household task added successfully!');
    }

    /**
     * Update the specified household task via AJAX.
     */
    public function update(Request $request, $id)
    {
        $task = HouseholdTask::where('customer_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'task_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
            'reminder_time' => 'required',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:pending,completed',
        ]);

        $task->update([
            'task_title' => $validated['task_title'],
            'description' => $validated['description'] ?? null,
            'reminder_date' => $validated['reminder_date'],
            'reminder_time' => $validated['reminder_time'],
            'priority' => $validated['priority'],
            'status' => $validated['status'] ?? $task->status,
        ]);

        // Log Activity
        ActivityLog::record(Auth::id(), 'Task Updated', "Updated household task: {$task->task_title}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Household task updated successfully!',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Household task updated successfully!');
    }

    /**
     * Update task status (Pending <-> Completed).
     */
    public function toggleStatus(Request $request, $id)
    {
        $task = HouseholdTask::where('customer_id', Auth::id())->findOrFail($id);
        
        if ($request->has('status') && in_array($request->status, ['pending', 'completed'])) {
            $task->status = $request->status;
        } else {
            $task->status = $task->status === 'pending' ? 'completed' : 'pending';
        }
        $task->save();

        ActivityLog::record(Auth::id(), 'Task Status Updated', "Updated status of task '{$task->task_title}' to {$task->status}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated!',
                'status' => $task->status,
            ]);
        }

        return back()->with('success', 'Task status updated!');
    }

    /**
     * Remove the specified household task via AJAX.
     */
    public function destroy(Request $request, $id)
    {
        $task = HouseholdTask::where('customer_id', Auth::id())->findOrFail($id);
        $taskTitle = $task->task_title;
        $task->delete();

        ActivityLog::record(Auth::id(), 'Task Deleted', "Deleted household task: {$taskTitle}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully!',
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}
