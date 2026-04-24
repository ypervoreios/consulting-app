<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Πίνακας ελέγχου</h1>
            <p class="text-gray-500 mt-1">Γενική εικόνα της εφαρμογής</p>
        </div>

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
                <p class="text-sm text-gray-500 mb-2">Εκπρόθεσμες Εργασίες</p>
                <a href="{{ route('tasks.overdue') }}" class="text-3xl font-bold text-red-600 hover:underline">
                    {{ $overdueTasks }}
                </a>
            </div>
        </div>

        <div class="mt-8 bg-white shadow rounded-xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Quick Links</h2>

            <div class="flex flex-wrap gap-3 mt-4">
                <a href="{{ route('clients.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Πελάτες
                </a>

                <a href="{{ route('projects.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Projects
                </a>

                <a href="{{ route('tasks.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Εργασίες
                </a>

                <a href="{{ route('tasks.my') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Οι εργασίες μου
                </a>

                <a href="{{ route('tasks.overdue') }}"
                   class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg">
                    Εκπροθέσμες εργασίες
                </a>
            </div>
        </div>
    </div>
</x-app-layout>