<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function index()
    {
        $users = User::with('manager')->get();

        return view('users.index', compact('users'));
    }

    public function edit(User $user)
{
    $managers = User::role('manager')->get();

    return view('users.edit', compact('user', 'managers'));
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required',
        'manager_id' => 'nullable|exists:users,id',
        'password' => 'nullable|string|min:8|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->manager_id = $request->manager_id;

    if ($request->filled('password')) {
    $user->password = Hash::make($request->password);
    }

    $user->save();

    $user->syncRoles([$request->role]);

    return redirect()
        ->route('users.index')
        ->with('success', 'Ο χρήστης ενημερώθηκε.');
}

public function destroy(User $user)
{
    if ($user->id === auth()->id()) {
        return redirect()
            ->route('users.index')
            ->with('success', 'Δεν μπορείς να διαγράψεις τον εαυτό σου.');
    }

    $user->delete();

    return redirect()
        ->route('users.index')
        ->with('success', 'Ο χρήστης διαγράφηκε.');
}

}
