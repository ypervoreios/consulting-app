<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Επεξεργασία χρήστη</h1>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <form method="POST"
                  action="{{ route('users.update', $user) }}"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Όνομα
                    </label>
                    <input
    type="text"
    name="name"
    value="{{ old('name', $user->name) }}"
    class="w-full border border-gray-300 rounded-lg px-3 py-2"
>
                </div>
            <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Email
    </label>

    <input
        type="email"
        name="email"
        value="{{ old('email', $user->email) }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2"
    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ρόλος
                    </label>

                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="admin"
                            {{ $user->hasRole('admin') ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="manager"
                            {{ $user->hasRole('manager') ? 'selected' : '' }}>
                            Manager
                        </option>

                        <option value="employee"
                            {{ $user->hasRole('employee') ? 'selected' : '' }}>
                            Employee
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Υπεύθυνος
                    </label>

                    <select name="manager_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- No Manager --</option>

                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}"
                                {{ $user->manager_id == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Νέος κωδικός
    </label>

    <input
        type="password"
        name="password"
        class="w-full border border-gray-300 rounded-lg px-3 py-2"
        placeholder="Άφησέ το κενό αν δεν θέλεις αλλαγή"
    >
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Επιβεβαίωση κωδικού
    </label>

    <input
        type="password"
        name="password_confirmation"
        class="w-full border border-gray-300 rounded-lg px-3 py-2"
    >
</div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg">
                        Αποθήκευση
                    </button>

                    <a href="{{ route('users.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Ακύρωση
                    </a>
                </div>
                
            </form>
        </div>
    </div>
</x-app-layout>