<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tasks</h1>

            <a href="{{ route('tasks.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg">
                + Νέα εργασία
            </a>
        </div>

        <div class="bg-white shadow rounded-xl p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Αναζήτηση</label>
                    <input
                        type="text"
                        name="Αναζήτηση"
                        placeholder="Αναζήτηση..."
                        value="{{ request('search') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Κατάσταση</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Όλες οι καταστάσεις</option>
                        <option value="Εκκρεμής" {{ request('status') == 'pending' ? 'selected' : '' }}>Εκκρεμής</option>
                        <option value="Σε εξέλιξη" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Σε εξέλιξη</option>
                        <option value="Ολοκληρωμένη" {{ request('status') == 'completed' ? 'selected' : '' }}>Ολοκληρωμένη</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Πελάτες</label>
                    <select name="client_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Όλοι οι πελάτες</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Όλα τα Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-3">
                    <button type="submit"
                            class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg">
                        Φίλτραρισμα
                    </button>

                    <a href="{{ route('tasks.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Επαναφορά
                    </a>
                </div>
            </form>
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
                            <th class="px-4 py-3">Κατασταση</th>
                            <th class="px-4 py-3">Προθεσμια</th>
                            <th class="px-4 py-3">Ενεργειες</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tasks as $task)
                            @php
                                $isOverdue = $task->due_date && $task->due_date < now() && $task->status !== 'completed';
                            @endphp

                            <tr class="hover:bg-gray-50
                                @if($isOverdue) bg-red-100
                                @elseif($task->status == 'completed') bg-green-100
                                @elseif($task->status == 'pending') bg-yellow-50
                                @endif">
                                <td class="px-4 py-3">{{ $task->project->client->company_name }}</td>
                                <td class="px-4 py-3">{{ $task->project->title }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <a href="{{ route('tasks.show', $task) }}" class="hover:underline">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $task->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                        @if($task->status == 'completed') bg-green-200 text-green-800
                                        @elseif($task->status == 'in_progress') bg-blue-200 text-blue-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $task->due_date ?? '-' }}</td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('tasks.edit', $task) }}"
                                       class="text-blue-600 hover:underline">
                                        Επεξεργασία
                                    </a>

                                    <form method="POST"
                                          action="{{ route('tasks.destroy', $task) }}"
                                          class="inline">
                                        @csrf
                                        @role('admin')
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Σίγουρα θέλεις διαγραφή;')"
                                                class="text-red-600 hover:underline">
                                            Διαγραφή
                                        </button>
                                        @endrole
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                    Δεν υπάρχουν tasks.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>