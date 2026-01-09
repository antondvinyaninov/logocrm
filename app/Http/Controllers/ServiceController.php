<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'session_type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline,both',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;
        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно создана');
    }

    public function edit(Service $service)
    {
        // Проверка доступа
        if ($service->organization_id !== auth()->user()->organization_id) {
            abort(403);
        }

        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        // Проверка доступа
        if ($service->organization_id !== auth()->user()->organization_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'session_type' => 'required|in:individual,group',
            'format' => 'required|in:online,offline,both',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно обновлена');
    }

    public function destroy(Service $service)
    {
        // Проверка доступа
        if ($service->organization_id !== auth()->user()->organization_id) {
            abort(403);
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Услуга успешно удалена');
    }
}
