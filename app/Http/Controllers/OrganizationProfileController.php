<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationProfileController extends Controller
{
    /**
     * Показать форму редактирования своей организации
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Только владельцы организаций могут редактировать
        if (!$user->isOrganization()) {
            abort(403, 'Только владельцы организаций могут редактировать профиль организации');
        }
        
        $organization = $user->organization;
        
        if (!$organization) {
            abort(404, 'Организация не найдена');
        }
        
        return view('organizations.edit-own', compact('organization'));
    }
    
    /**
     * Обновить данные своей организации
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Только владельцы организаций могут редактировать
        if (!$user->isOrganization()) {
            abort(403, 'Только владельцы организаций могут редактировать профиль организации');
        }
        
        $organization = $user->organization;
        
        if (!$organization) {
            abort(404, 'Организация не найдена');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        // Обработка загрузки логотипа
        if ($request->hasFile('logo')) {
            // Удаляем старый логотип
            if ($organization->logo) {
                Storage::disk('public')->delete($organization->logo);
            }
            
            $validated['logo'] = $request->file('logo')->store('organizations', 'public');
        }
        
        $organization->update($validated);
        
        return redirect()
            ->route('organizations.edit-own')
            ->with('success', 'Профиль организации успешно обновлен');
    }
}
