<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Νέα εργασία</h1>
            <p class="text-gray-500 mt-1">Δημιούργησε μία νέα εργασία και ανάθεσέ τη σε χρήστη</p>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select name="project_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- Διάλεξε project --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->title }} - {{ $project->client->company_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Περιγραφή</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ανάθεση χρήστη</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- Επιλογή χρήστη --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Κατάσταση</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="Εκκρεμής" {{ old('status') == 'pending' ? 'selected' : '' }}>Εκκρεμής</option>
                        <option value="Σε εξέλιξη" {{ old('status') == 'in_progress' ? 'selected' : '' }}>Σε εξέλιξη</option>
                        <option value="Ολοκληρωμένη" {{ old('status') == 'completed' ? 'selected' : '' }}>Ολοκληρωμένη</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Προθεσμία</label>
                    <input
                        type="date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg"
                    >
                        Αποθήκευση
                    </button>

                    <a
                        href="{{ route('tasks.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg"
                    >
                        Ακύρωση
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>