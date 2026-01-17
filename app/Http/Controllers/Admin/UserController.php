<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\SpecialistProfile;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        $query = User::query();

        // Фильтр по роли
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Поиск по email или имени
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->with(['specialistProfile', 'parentProfile'])
                       ->latest()
                       ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Создаём профиль в зависимости от роли
        if ($request->role === 'specialist' && $request->filled('full_name')) {
            SpecialistProfile::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'specialization' => $request->specialization,
            ]);
        } elseif ($request->role === 'parent' && $request->filled('full_name')) {
            ParentProfile::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Пользователь успешно создан');
    }

    public function show(User $user)
    {
        $user->load(['specialistProfile', 'parentProfile']);
        
        // Загружаем клиентов для специалиста
        $children = null;
        if ($user->role === 'specialist' && $user->specialistProfile) {
            $children = $user->specialistProfile->children()->with(['parent'])->get();
        }
        
        // Загружаем детей для родителя
        if ($user->role === 'parent' && $user->parentProfile) {
            $children = $user->parentProfile->children()->with(['specialist'])->get();
        }
        
        return view('admin.users.show', compact('user', 'children'));
    }

    public function edit(User $user)
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        $user->load(['specialistProfile', 'parentProfile']);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Обновляем профиль
        if ($request->role === 'specialist') {
            $user->specialistProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $request->full_name,
                    'specialization' => $request->specialization,
                ]
            );
        } elseif ($request->role === 'parent') {
            $user->parentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $request->full_name,
                    'phone' => $request->phone,
                ]
            );
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Пользователь успешно обновлён');
    }

    public function destroy(User $user)
    {
        // Проверка роли: только superadmin и organization могут управлять пользователями
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isOrganization()) {
            abort(403, 'Доступ запрещён. Только администраторы могут управлять пользователями.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете удалить свой аккаунт');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Пользователь успешно удалён');
    }

    /**
     * Войти как пользователь (impersonate)
     */
    public function impersonate(User $user)
    {
        // Только superadmin может входить как другие пользователи
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Только суперадминистратор может использовать эту функцию.');
        }

        // Нельзя войти как самого себя
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Вы не можете войти как самого себя.');
        }

        // Сохраняем ID оригинального пользователя в сессии
        session(['impersonate_original_user' => auth()->id()]);

        // Входим как выбранный пользователь
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', "Вы вошли как {$user->name}. Для возврата нажмите кнопку в верхнем меню.");
    }

    /**
     * Вернуться к своему аккаунту
     */
    public function stopImpersonate()
    {
        if (!session()->has('impersonate_original_user')) {
            return redirect()->route('dashboard');
        }

        $originalUserId = session('impersonate_original_user');
        session()->forget('impersonate_original_user');

        $originalUser = User::find($originalUserId);
        
        if ($originalUser) {
            auth()->login($originalUser);
            return redirect()->route('admin.users.index')->with('success', 'Вы вернулись к своему аккаунту.');
        }

        return redirect()->route('dashboard');
    }
}
