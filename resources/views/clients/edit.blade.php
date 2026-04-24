<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Client</h1>
            <p class="text-gray-500 mt-1">Ενημέρωσε τα στοιχεία του πελάτη</p>
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

            <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Επωνυμία</label>
                    <input
                        type="text"
                        name="company_name"
                        value="{{ old('company_name', $client->company_name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input
                        type="text"
                        name="email"
                        value="{{ old('email', $client->email) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Τηλέφωνο</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $client->phone) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2"
                    >
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg"
                    >
                        Ενημέρωση
                    </button>

                    <a
                        href="{{ route('clients.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg"
                    >
                        Ακύρωση
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>