<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeamController extends Controller
{
    public function index()
    {
        // Только Organization может управлять командой
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        $users = User::where('organization_id', auth()->user()->organization_id)
            ->with(['specialistProfile', 'parentProfile'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('team.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        return view('team.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:specialist,parent'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'organization_id' => auth()->user()->organization_id,
        ]);

        return redirect()->route('team.index')
            ->with('success', 'Пользователь успешно создан');
    }

    public function show(User $user)
    {
        // Проверяем, что пользователь из той же организации
        if ($user->organization_id !== auth()->user()->organization_id && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        return view('team.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        if ($user->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        return view('team.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        if ($user->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:specialist,parent'],
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Rules\Password::defaults()],
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('team.index')
            ->with('success', 'Пользователь успешно обновлен');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        if ($user->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $user->delete();

        return redirect()->route('team.index')
            ->with('success', 'Пользователь успешно удален');
    }
}
