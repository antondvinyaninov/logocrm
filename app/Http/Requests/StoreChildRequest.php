<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth()->user();
        
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
        ];

        // Родитель не может выбирать parent_id (автоматически)
        if ($user->role !== 'parent') {
            $rules['parent_id'] = ['required', 'exists:parent_profiles,id'];
            $rules['anamnesis'] = ['nullable', 'string'];
            $rules['goals'] = ['nullable', 'string'];
            $rules['tags'] = ['nullable', 'array'];
            $rules['tags.*'] = ['string', 'max:50'];
        }

        // Родители и админ могут выбирать специалиста
        if ($user->isSuperAdmin() || $user->isOrganization() || $user->role === 'parent') {
            $rules['specialist_id'] = ['nullable', 'exists:specialist_profiles,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'ФИО ребёнка обязательно для заполнения',
            'birth_date.required' => 'Дата рождения обязательна для заполнения',
            'birth_date.before' => 'Дата рождения должна быть в прошлом',
            'parent_id.required' => 'Необходимо выбрать родителя',
            'parent_id.exists' => 'Выбранный родитель не найден',
            'specialist_id.exists' => 'Выбранный специалист не найден',
        ];
    }
}
