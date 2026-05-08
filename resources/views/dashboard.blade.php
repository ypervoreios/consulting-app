<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Πίνακας ελέγχου</h1>
            <p class="text-gray-500 mt-1">
                Καλώς ήρθες, {{ auth()->user()->name }}
            </p>
        </div>

        @if($dashboardType === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-2">Πελάτες</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $clientsCount }}</h2>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500 mb-2">Projects</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $projectsCount }}</h2>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-2">Εργασίες</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $tasksCount }}</h2>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 mb-2">Εκπρόθεσμες εργασίες</p>
                    <a href="{{ route('tasks.overdue') }}" class="text-3xl font-bold text-red-600 hover:underline">
                        {{ $overdueTasks }}
                    </a>
                </div>
            </div>

            <div class="mt-8 bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Links</h2>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('clients.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Πελάτες
                    </a>

                    <a href="{{ route('projects.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Projects
                    </a>

                    <a href="{{ route('tasks.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Εργασίες
                    </a>

                    <a href="{{ route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                        Χρήστες
                    </a>

                    <a href="{{ route('tasks.overdue') }}" class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg">
                        Εκπρόθεσμες εργασίες
                    </a>
                </div>
            </div>
        @endif

        @if($dashboardType === 'manager')
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-2">Πελάτες</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $employeesCount }}</h2>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500 mb-2">Εργασίες ομάδας</p>
                    <a href="{{ route('tasks.team') }}" class="text-3xl font-bold text-gray-800 hover:underline">
                        {{ $teamTasksCount }}
                    </a>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 mb-2">Προθεσμίες ομάδας</p>
                    <a href="{{ route('tasks.overdue') }}" class="text-3xl font-bold text-red-600 hover:underline">
                        {{ $teamOverdueTasks }}
                    </a>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-2">Ολοκληρωμένες</p>
                    <h2 class="text-3xl font-bold text-green-600">{{ $teamCompletedTasks }}</h2>
                </div>
            </div>

            <div class="mt-8 bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Πρόσφατες εργασίες ομάδας</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Χρηστης</th>
                                <th class="px-4 py-3">Πελατης</th>
                                <th class="px-4 py-3">Project</th>
                                <th class="px-4 py-3">Εργασια</th>
                                <th class="px-4 py-3">Κατασταση</th>
                                <th class="px-4 py-3">Προθεσμια</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentTeamTasks as $task)
                                @php
                                    $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'completed';
                                @endphp

                                <tr class="hover:bg-gray-50 @if($isOverdue) bg-red-50 @endif">
                                    <td class="px-4 py-3">{{ $task->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $task->project->client->company_name }}</td>
                                    <td class="px-4 py-3">{{ $task->project->title }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $task->status }}</td>
                                    <td class="px-4 py-3 @if($isOverdue) text-red-700 font-medium @endif">
                                        {{ $task->due_date ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        Δεν υπάρχουν πρόσφατα tasks ομάδας.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($dashboardType === 'employee')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-2">Οι εργασίες μου</p>
                    <a href="{{ route('tasks.my') }}" class="text-3xl font-bold text-gray-800 hover:underline">
                        {{ $myTasksCount }}
                    </a>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 mb-2">Εκπρόθεσμες</p>
                    <h2 class="text-3xl font-bold text-red-600">{{ $myOverdueTasks }}</h2>
                </div>

                <div class="bg-white shadow rounded-xl p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-2">Ολοκληρωμένες</p>
                    <h2 class="text-3xl font-bold text-green-600">{{ $myCompletedTasks }}</h2>
                </div>
            </div>

            <div class="mt-8 bg-white shadow rounded-xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Οι εργασίες μου</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Πελατης</th>
                                <th class="px-4 py-3">Project</th>
                                <th class="px-4 py-3">Εργασια</th>
                                <th class="px-4 py-3">Κατασταση</th>
                                <th class="px-4 py-3">Προθεσμια</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($myRecentTasks as $task)
                                @php
                                    $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'completed';
                                @endphp

                                <tr class="hover:bg-gray-50 @if($isOverdue) bg-red-50 @endif">
                                    <td class="px-4 py-3">{{ $task->project->client->company_name }}</td>
                                    <td class="px-4 py-3">{{ $task->project->title }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                            {{ $task->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $task->status }}</td>
                                    <td class="px-4 py-3 @if($isOverdue) text-red-700 font-medium @endif">
                                        {{ $task->due_date ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Δεν έχεις πρόσφατα tasks.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>