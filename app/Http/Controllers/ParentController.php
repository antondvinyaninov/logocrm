<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Получаем родителей в зависимости от роли
        if ($user->isOrganization()) {
            $parents = User::where('organization_id', $user->organization_id)
                ->where('role', 'parent')
                ->with('parentProfile')
                ->orderBy('name')
                ->paginate(20);
        } elseif ($user->isSpecialist()) {
            // Специалист видит родителей своих клиентов
            $parents = User::where('organization_id', $user->organization_id)
                ->where('role', 'parent')
                ->with('parentProfile')
                ->orderBy('name')
                ->paginate(20);
        } else {
            abort(403);
        }
        
        return view('parents.index', compact('parents'));
    }

    public function show(User $parent)
    {
        $user = Auth::user();
        
        // Проверка доступа
        if ($parent->role !== 'parent' || $parent->organization_id !== $user->organization_id) {
            abort(403);
        }
        
        $parent->load('parentProfile');
        
        // Получаем детей родителя через parentProfile
        $children = collect();
        if ($parent->parentProfile) {
            $children = \App\Models\Child::where('parent_id', $parent->parentProfile->id)
                ->with(['specialist.user'])
                ->get();
        }
        
        return view('parents.show', compact('parent', 'children'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isOrganization() && !$user->isSpecialist()) {
            abort(403);
        }
        
        return view('parents.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isOrganization() && !$user->isSpecialist()) {
            abort(403);
        }
        
        // Для организаций пароль обязателен, для специалистов - нет
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'children' => 'nullable|array',
            'children.*.name' => 'required|string|max:255',
            'children.*.age' => 'required|integer|min:0|max:18',
            'children.*.diagnosis' => 'nullable|string|max:255',
        ];
        
        if ($user->isOrganization()) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }
        
        $validated = $request->validate($rules);
        
        // Создаем родителя
        $parentData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'parent',
            'organization_id' => $user->organization_id,
        ];
        
        // Организация создает полноценную учетную запись с паролем
        if ($user->isOrganization() && !empty($validated['password'])) {
            $parentData['password'] = Hash::make($validated['password']);
        } else {
            // Специалист добавляет родителя как контакт без пароля
            // Родитель сможет зарегистрироваться позже
            $parentData['password'] = null;
        }
        
        $parent = User::create($parentData);
        
        // Создаем профиль родителя
        $parent->parentProfile()->create([
            'phone' => $validated['phone'] ?? null,
        ]);
        
        // Создаем детей если они указаны
        if (!empty($validated['children'])) {
            foreach ($validated['children'] as $childData) {
                \App\Models\Child::create([
                    'name' => $childData['name'],
                    'age' => $childData['age'],
                    'diagnosis' => $childData['diagnosis'] ?? null,
                    'parent_id' => $parent->id,
                    'organization_id' => $user->organization_id,
                ]);
            }
        }
        
        return redirect()->route('parents.index')
            ->with('success', 'Родитель успешно добавлен');
    }

    public function edit(User $parent)
    {
        $user = Auth::user();
        
        if (!$user->isOrganization() && !$user->isSpecialist()) {
            abort(403);
        }
        
        if ($parent->role !== 'parent' || $parent->organization_id !== $user->organization_id) {
            abort(403);
        }
        
        $parent->load('parentProfile');
        
        return view('parents.edit', compact('parent'));
    }

    public function update(Request $request, User $parent)
    {
        $user = Auth::user();
        
        if (!$user->isOrganization() && !$user->isSpecialist()) {
            abort(403);
        }
        
        if ($parent->role !== 'parent' || $parent->organization_id !== $user->organization_id) {
            abort(403);
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->id,
            'phone' => 'nullable|string|max:20',
        ];
        
        // Только организация может менять пароль
        if ($user->isOrganization()) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }
        
        $validated = $request->validate($rules);
        
        $parent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        // Только организация может обновить пароль
        if ($user->isOrganization() && !empty($validated['password'])) {
            $parent->update(['password' => Hash::make($validated['password'])]);
        }
        
        $parent->parentProfile()->updateOrCreate(
            ['user_id' => $parent->id],
            ['phone' => $validated['phone'] ?? null]
        );
        
        return redirect()->route('parents.index')
            ->with('success', 'Данные родителя обновлены');
    }

    public function destroy(User $parent)
    {
        $user = Auth::user();
        
        if (!$user->isOrganization() || $parent->role !== 'parent' || $parent->organization_id !== $user->organization_id) {
            abort(403);
        }
        
        $parent->delete();
        
        return redirect()->route('parents.index')
            ->with('success', 'Родитель удален');
    }
}
