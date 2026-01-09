<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Child;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $query = Payment::with(['child.parent']);

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Фильтр по ребёнку
        if ($request->filled('child_id')) {
            $query->where('child_id', $request->child_id);
        }

        // Фильтр по дате
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(20);
        $children = Child::with('parent')->get();

        // Статистика
        $stats = [
            'total' => Payment::sum('amount'),
            'paid' => Payment::where('status', 'paid')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'children', 'stats'));
    }

    public function create()
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $children = Child::with('parent')->get();
        return view('admin.payments.create', compact('children'));
    }

    public function store(Request $request)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,cancelled',
            'description' => 'nullable|string|max:500',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Платёж создан');
    }

    public function show(Payment $payment)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $payment->load(['child.parent']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $children = Child::with('parent')->get();
        return view('admin.payments.edit', compact('payment', 'children'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,cancelled',
            'description' => 'nullable|string|max:500',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Платёж обновлён');
    }

    public function destroy(Payment $payment)
    {
        // Проверка роли: только superadmin и organization могут управлять платежами
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isOrganization()) {
            abort(403, 'Доступ запрещён');
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Платёж удалён');
    }
}
