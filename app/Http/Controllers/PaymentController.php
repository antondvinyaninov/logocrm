<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TherapySession;
use App\Models\Child;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Organization и Specialist видят платежи своей организации
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        $organizationId = auth()->user()->organization_id;
        
        // Получаем завершенные и подтвержденные занятия за текущий месяц
        // Показываем: done (завершено) или confirmed (подтверждено, родитель пришел)
        $completedSessions = TherapySession::where('organization_id', $organizationId)
            ->whereIn('status', ['done', 'confirmed'])
            ->whereMonth('start_time', Carbon::now()->month)
            ->whereYear('start_time', Carbon::now()->year)
            ->with(['child.parent', 'specialist.user'])
            ->orderBy('start_time', 'desc')
            ->get();
        
        // Статистика
        $totalDue = $completedSessions->where('payment_status', 'unpaid')->sum('price');
        $totalPaid = $completedSessions->where('payment_status', 'paid')->sum('price');
        
        // Должники (дети с неоплаченными занятиями)
        $debtors = $completedSessions->where('payment_status', 'unpaid')
            ->groupBy('child_id')
            ->map(function ($sessions) {
                $child = $sessions->first()->child;
                return [
                    'child' => $child,
                    'debt' => $sessions->sum('price'),
                    'sessions_count' => $sessions->count(),
                ];
            })->values();
        
        // План на следующий месяц (запланированные занятия)
        $nextMonthStart = Carbon::now()->addMonth()->startOfMonth();
        $nextMonthEnd = Carbon::now()->addMonth()->endOfMonth();
        
        $nextMonthSessions = TherapySession::where('organization_id', $organizationId)
            ->whereBetween('start_time', [$nextMonthStart, $nextMonthEnd])
            ->whereIn('status', ['planned', 'confirmed'])
            ->get();
        
        $nextMonthPlan = $nextMonthSessions->sum('price');
        
        // Фильтр должников
        $showDebtors = $request->has('debtors');

        return view('payments.index', compact(
            'completedSessions',
            'totalDue',
            'totalPaid',
            'debtors',
            'nextMonthPlan',
            'showDebtors'
        ));
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
    
    public function markAsPaid(Request $request, TherapySession $session)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        if ($session->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $session->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Занятие отмечено как оплаченное');
    }
    
    public function markAsUnpaid(Request $request, TherapySession $session)
    {
        if (!auth()->user()->isOrganization() && !auth()->user()->isSpecialist()) {
            abort(403, 'Доступ запрещен');
        }

        if ($session->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Доступ запрещен');
        }

        $session->update([
            'payment_status' => 'unpaid',
            'paid_at' => null,
        ]);

        return back()->with('success', 'Занятие отмечено как неоплаченное');
    }
}
