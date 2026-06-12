<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
{
    $query = Task::with('project.client', 'user');

    // Admin βλέπει όλα
    if (auth()->user()->hasRole('admin')) {

        // nothing

    }
    // Manager βλέπει tasks της ομάδας του
    elseif (auth()->user()->hasRole('manager')) {

        $employeeIds = auth()->user()->employees()->pluck('id');

        $query->whereIn('user_id', $employeeIds);

    }
    // Employee βλέπει μόνο τα δικά του
    else {

        $query->where('user_id', auth()->id());
    }

    // Search
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Status filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Client filter
    if ($request->filled('client_id')) {
        $query->whereHas('project.client', function ($q) use ($request) {
            $q->where('id', $request->client_id);
        });
    }

    // Project filter
    if ($request->filled('project_id')) {
        $query->where('project_id', $request->project_id);
    }

    $tasks = $query->latest()->get();

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
    // If the documents.task_id column doesn't exist yet (migration not run),
    // avoid eager-loading documents to prevent SQL errors.
    if (Schema::hasColumn('documents', 'task_id')) {
        $task->load('project.client', 'comments.user', 'documents');
    } else {
        $task->load('project.client', 'comments.user');
    }

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

     $query = Task::with('project.client', 'user')
        ->where('due_date', '<', now())
        ->where('status', '!=', 'completed');

    // Αν είναι manager, δείξε μόνο tasks της ομάδας του
    if (auth()->user()->hasRole('manager')) {
        $employeeIds = auth()->user()->employees()->pluck('id');

        $query->whereIn('user_id', $employeeIds);
    }

    $tasks = $query->get();

    return view('tasks.overdue', compact('tasks'));
    }

    public function myTasks()
    {
    $tasks = Task::with('project.client')
        ->where('user_id', Auth::id())
        ->get();

    return view('tasks.my', compact('tasks'));
    }

    public function teamTasks()
    {
    if (!auth()->user()->hasAnyRole(['admin', 'manager'])) {
        abort(403);
    }

    $query = Task::with('project.client', 'user');

    if (auth()->user()->hasRole('manager')) {
        $employeeIds = auth()->user()->employees()->pluck('id');
        $query->whereIn('user_id', $employeeIds);
    }

    $tasks = $query->get();

    return view('tasks.team', compact('tasks'));
    }
}
