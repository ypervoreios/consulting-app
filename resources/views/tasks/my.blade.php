<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Οι εργασίες μου</h1>
                <p class="text-gray-500 mt-1">Οι εργασίες που είναι ανατεθειμένες σε εσένα</p>
            </div>

            <a href="{{ route('tasks.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                Όλες οι εργασίες
            </a>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white shadow rounded-xl p-4 border-l-4 border-blue-500">
                        <p class="text-sm text-gray-500">Σύνολο εργασιών</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $tasks->count() }}</p>
                    </div>
                
                    <div class="bg-white shadow rounded-xl p-4 border-l-4 border-red-500">
                        <p class="text-sm text-gray-500">Εκπρόθεσμες</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ $tasks->filter(fn($task) => $task->due_date && $task->due_date < now() && $task->status !== 'completed')->count() }}
                        </p>
                    </div>
                
                    <div class="bg-white shadow rounded-xl p-4 border-l-4 border-green-500">
                        <p class="text-sm text-gray-500">Ολοκληρωμένες</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $tasks->where('status', 'completed')->count() }}
                        </p>
                    </div>
                </div>
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
                        @forelse ($tasks as $task)
                            @php
                                $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'completed';
                            @endphp

                            <tr class="hover:bg-gray-50
                                @if($isOverdue) bg-red-50
                                @elseif($task->status == 'completed') bg-green-50
                                @elseif($task->status == 'pending') bg-yellow-50
                                @endif">
                                <td class="px-4 py-3">{{ $task->project->client->company_name }}</td>
                                <td class="px-4 py-3">{{ $task->project->title }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                        @if($task->status == 'completed') bg-green-200 text-green-800
                                        @elseif($task->status == 'in_progress') bg-blue-200 text-blue-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3
                                    @if($isOverdue) text-red-700 font-medium @endif">
                                    {{ $task->due_date ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Δεν έχεις εργασίες 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>