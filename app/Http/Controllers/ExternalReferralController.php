<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\ExternalSpecialistReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExternalReferralController extends Controller
{
    public function store(Request $request, Child $child)
    {
        $user = Auth::user();
        
        // Проверка доступа
        if ($child->organization_id !== $user->organization_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'specialist_type' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'referral_date' => 'required|date',
            'appointment_date' => 'nullable|date|after_or_equal:referral_date',
        ]);
        
        $referral = ExternalSpecialistReferral::create([
            'child_id' => $child->id,
            'organization_id' => $user->organization_id,
            'created_by' => $user->id,
            'specialist_type' => $validated['specialist_type'],
            'reason' => $validated['reason'] ?? null,
            'referral_date' => $validated['referral_date'],
            'appointment_date' => $validated['appointment_date'] ?? null,
            'status' => $validated['appointment_date'] ? 'scheduled' : 'pending',
        ]);
        
        return redirect()->route('children.show', $child)
            ->with('success', 'Направление к специалисту создано');
    }
    
    public function update(Request $request, Child $child, ExternalSpecialistReferral $referral)
    {
        $user = Auth::user();
        
        // Проверка доступа
        if ($referral->organization_id !== $user->organization_id || $referral->child_id !== $child->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'appointment_date' => 'nullable|date',
            'visit_date' => 'nullable|date',
            'results' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);
        
        $updateData = [
            'status' => $validated['status'],
            'appointment_date' => $validated['appointment_date'] ?? null,
            'visit_date' => $validated['visit_date'] ?? null,
            'results' => $validated['results'] ?? null,
            'recommendations' => $validated['recommendations'] ?? null,
        ];
        
        // Обработка файлов
        if ($request->hasFile('attachments')) {
            $attachments = $referral->attachments ?? [];
            
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('referrals/' . $child->id, 'public');
                $attachments[] = $path;
            }
            
            $updateData['attachments'] = $attachments;
        }
        
        $referral->update($updateData);
        
        return redirect()->route('children.show', $child)
            ->with('success', 'Информация о направлении обновлена');
    }
    
    public function destroy(Child $child, ExternalSpecialistReferral $referral)
    {
        $user = Auth::user();
        
        // Проверка доступа
        if ($referral->organization_id !== $user->organization_id || $referral->child_id !== $child->id) {
            abort(403);
        }
        
        // Удаляем прикрепленные файлы
        if ($referral->attachments) {
            foreach ($referral->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }
        
        $referral->delete();
        
        return redirect()->route('children.show', $child)
            ->with('success', 'Направление удалено');
    }
}
