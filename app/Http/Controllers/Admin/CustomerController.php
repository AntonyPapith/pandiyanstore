<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        $query = User::query()->where('is_admin', false)->withCount('orders')
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($nested) => $nested
                ->where('name', 'like', '%'.$request->string('search').'%')
                ->orWhere('email', 'like', '%'.$request->string('search').'%')
                ->orWhere('phone', 'like', '%'.$request->string('search').'%')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')))
            ->latest();

        if ($request->string('export')->toString() === 'csv') {
            return response()->streamDownload(function () use ($query): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Name', 'Email', 'Phone', 'Orders', 'Joined']);
                $query->get()->each(fn (User $user) => fputcsv($out, [$user->name, $user->email, $user->phone, $user->orders_count, $user->created_at->format('Y-m-d')]));
                fclose($out);
            }, 'pandiyan-store-users.csv', ['Content-Type' => 'text/csv']);
        }

        $customers = $query->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        abort_if($customer->is_admin, 404);

        return view('admin.customers.show', ['customer' => $customer->load(['customerDetail', 'orders.items'])]);
    }

    public function edit(User $customer): View
    {
        abort_if($customer->is_admin, 404);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer): RedirectResponse
    {
        abort_if($customer->is_admin, 404);
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:30']]);
        $customer->update($data);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer details updated successfully.');
    }

    public function destroy(User $customer): RedirectResponse
    {
        abort_if($customer->is_admin, 404);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
