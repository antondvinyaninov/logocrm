<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationPublicController extends Controller
{
    /**
     * Публичный профиль организации
     */
    public function show($id)
    {
        $organization = Organization::with([
            'specialists.user',
            'specialists.reviews'
        ])
        ->withCount(['specialists', 'children'])
        ->findOrFail($id);

        // Проверяем, что организация активна
        if (!$organization->is_active) {
            abort(404, 'Организация не найдена');
        }

        return view('organizations.public.show', compact('organization'));
    }
}
