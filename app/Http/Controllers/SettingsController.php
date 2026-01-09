<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Доступ для Organization и Specialist
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        $organization = null;
        if (auth()->user()->isOrganization()) {
            $organization = auth()->user()->organization;
        }

        return view('settings.index', compact('organization'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isOrganization()) {
            abort(403, 'Доступ запрещен');
        }

        $organization = auth()->user()->organization;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:center,individual'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'description' => ['nullable', 'string'],
            'working_hours' => ['nullable', 'string'],
        ]);

        $organization->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Настройки успешно обновлены');
    }
}
