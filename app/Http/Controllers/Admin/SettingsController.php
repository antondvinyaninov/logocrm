<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Проверка роли: только superadmin и organization могут видеть настройки
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        // Проверка роли: только superadmin и organization могут изменять настройки
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        // Здесь будет логика сохранения настроек
        // Пока просто заглушка

        return back()->with('success', 'Настройки сохранены');
    }
}
