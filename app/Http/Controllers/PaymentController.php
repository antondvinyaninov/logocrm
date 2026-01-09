<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Organization и Specialist видят платежи своей организации
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        $payments = Payment::where('organization_id', auth()->user()->organization_id)
            ->with(['child', 'parent'])
            ->orderBy('payment_date', 'desc')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        return view('payments.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'child_id' => ['required', 'exists:children,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string'],
            'status' => ['required', 'in:pending,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Платеж успешно создан');
    }

    public function show(Payment $payment)
    {
        if ($payment->organization_id !== auth()->user()->organization_id && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        if ($payment->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        if ($payment->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string'],
            'status' => ['required', 'in:pending,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Платеж успешно обновлен');
    }

    public function destroy(Payment $payment)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        if ($payment->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Платеж успешно удален');
    }
}
