<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Χρήστες</h1>
                <p class="text-gray-500 mt-1">Διαχείριση χρηστών και ομάδων</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Ονομα</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Ρολος</th>
                            <th class="px-4 py-3">Υπευθυνος</th>
                            <th class="px-4 py-3">Ενεργειες</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="inline-block px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                </td>

                                <td class="px-4 py-3">
                                    {{ $user->manager->name ?? '-' }}
                                </td>
<td class="px-4 py-3 space-x-2">
    <a href="{{ route('users.edit', $user) }}"
       class="text-blue-600 hover:underline">
        Επεξεργασία
    </a>

    @if($user->id !== auth()->id())
        <form method="POST"
              action="{{ route('users.destroy', $user) }}"
              class="inline">
            @csrf
            @method('DELETE')

            <button type="submit"
                    onclick="return confirm('Σίγουρα θέλεις να διαγράψεις αυτόν τον χρήστη;')"
                    class="text-red-600 hover:underline">
                Διαγραφή
            </button>
        </form>
    @endif
</td>

                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>