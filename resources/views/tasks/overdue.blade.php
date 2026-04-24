<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Εκπρόθεσμες εργασίες</h1>
                <p class="text-gray-500 mt-1">Εργασίες που έχουν περάσει την προθεσμία τους</p>
            </div>

            <a href="{{ route('tasks.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                Πίσω στις εργασίες
            </a>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Πελατης</th>
                            <th class="px-4 py-3">Project</th>
                            <th class="px-4 py-3">Εργασια</th>
                            <th class="px-4 py-3">Χρηστης</th>
                            <th class="px-4 py-3">Προθεσμια</th>
                            <th class="px-4 py-3">Κατασταση</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tasks as $task)
                            <tr class="bg-red-50 hover:bg-red-100">
                                <td class="px-4 py-3">{{ $task->project->client->company_name }}</td>
                                <td class="px-4 py-3">{{ $task->project->title }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $task->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-red-700 font-medium">{{ $task->due_date }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium bg-red-200 text-red-800">
                                        {{ $task->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Δεν υπάρχουν εκπρόθεσμες εργασίες! 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>