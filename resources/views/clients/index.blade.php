<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Πελάτες</h1>

            <a href="{{ route('clients.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg">
                + Νέος Πελάτης
            </a>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Εταιρεια</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Ενεργειες</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $client->company_name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $client->email ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $client->phone ?? '-' }}
                                </td>

                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('clients.edit', $client) }}"
                                       class="text-blue-600 hover:underline">
                                        Επεξεργασία
                                    </a>

                                    <form method="POST"
                                          action="{{ route('clients.destroy', $client) }}"
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
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    Δεν υπάρχουν πελάτες.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>