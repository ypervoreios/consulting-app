<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with('project.client', 'user');

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('project', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%$search%")
                         ->orWhereHas('client', function ($q3) use ($search) {
                             $q3->where('company_name', 'like', "%$search%");
                         });
                  });
            });
        }

        // filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // filter client
        if ($request->client_id) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // filter project
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->get();

        $clients = \App\Models\Client::all();
        $projects = \App\Models\Project::all();

        return view('tasks.index', compact('tasks', 'clients', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::with('client')->get();
        $users = User::all();

    return view('tasks.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required',
            'status' => 'required',
            'due_date' => 'nullable|date',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Η εργασία αποθηκεύτηκε.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
    $task->load('project.client', 'comments.user');

    return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::with('client')->get();
    $users = User::all();

    return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required',
            'status' => 'required',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Η εργασία ενημερώθηκε.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Η εργασία διαγράφηκε.');
    }

    public function overdue()
    {

    $tasks = Task::with('project.client', 'user')
        ->where('due_date', '<', now())
        ->where('status', '!=', 'completed')
        ->get();

    return view('tasks.overdue', compact('tasks'));
    }

    public function myTasks()
    {
    $tasks = Task::with('project.client')
        ->where('user_id', Auth::id())
        ->get();

    return view('tasks.my', compact('tasks'));
    }
}
