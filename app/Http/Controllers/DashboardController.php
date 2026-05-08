<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        if ($user->hasRole('manager')) {
            return $this->managerDashboard();
        }

        return redirect()->route('tasks.my');
    }

    private function adminDashboard()
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
        ))->with('dashboardType', 'admin');
    }

    private function managerDashboard()
    {
        $manager = auth()->user();

        $employeeIds = $manager->employees()->pluck('id');

        $employeesCount = $employeeIds->count();

        $teamTasksCount = Task::whereIn('user_id', $employeeIds)->count();

        $teamOverdueTasks = Task::whereIn('user_id', $employeeIds)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        $teamCompletedTasks = Task::whereIn('user_id', $employeeIds)
            ->where('status', 'completed')
            ->count();

        $recentTeamTasks = Task::with('project.client', 'user')
            ->whereIn('user_id', $employeeIds)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'employeesCount',
            'teamTasksCount',
            'teamOverdueTasks',
            'teamCompletedTasks',
            'recentTeamTasks'
        ))->with('dashboardType', 'manager');
    }

    private function employeeDashboard()
    {
        $user = auth()->user();

        $myTasksCount = Task::where('user_id', $user->id)->count();

        $myOverdueTasks = Task::where('user_id', $user->id)
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();

        $myCompletedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $myRecentTasks = Task::with('project.client')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'myTasksCount',
            'myOverdueTasks',
            'myCompletedTasks',
            'myRecentTasks'
        ))->with('dashboardType', 'employee');
    }
}