<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Έργα</h1>

            <a href="{{ route('projects.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg">
                + Νέο Project
            </a>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Πελατης</th>
                            <th class="px-4 py-3">Περιγραφη</th>
                            <th class="px-4 py-3">Κατασταση</th>
                            <th class="px-4 py-3">Προθεσμια</th>
                            <th class="px-4 py-3">Ενεργειες</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            @php
                                $isOverdue = $project->due_date && $project->due_date < now() && $project->status !== 'completed';
                            @endphp

                            <tr class="hover:bg-gray-50
                                @if($isOverdue) bg-red-50
                                @elseif($project->status == 'completed') bg-green-50
                                @elseif($project->status == 'pending') bg-yellow-50
                                @endif">
                                <td class="px-4 py-3">{{ $project->client->company_name }}</td>

                                <td class="px-4 py-3 font-medium text-gray-900">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:underline">
                                        {{ $project->title }}
                                    </a>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                                        @if($project->status == 'completed') bg-green-200 text-green-800
                                        @elseif($project->status == 'in_progress') bg-blue-200 text-blue-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ $project->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">{{ $project->due_date ?? '-' }}</td>

                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('projects.edit', $project) }}"
                                       class="text-blue-600 hover:underline">
                                        Επεξεργασία
                                    </a>

                                    <form method="POST"
                                          action="{{ route('projects.destroy', $project) }}"
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
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Δεν υπάρχουν projects.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>