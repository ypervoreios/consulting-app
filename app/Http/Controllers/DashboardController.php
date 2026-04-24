<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $clientsCount = Client::count();
        $projectsCount = Project::count();
        $tasksCount = Task::count();

        $overdueTasks = Task::where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        return view('dashboard', compact(
            'clientsCount',
            'projectsCount',
            'tasksCount',
            'overdueTasks'
        ));
    }
}