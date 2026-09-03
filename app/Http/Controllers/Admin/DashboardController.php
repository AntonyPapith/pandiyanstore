<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'categoryCount' => Category::count(),
            'productCount' => Product::count(),
            'customerCount' => User::where('is_admin', false)->count(),
            'orderCount' => Order::count(),
            'orderValue' => Order::sum('total_amount'),
            'pendingPaymentCount' => Order::where('payment_status', 'pending_verification')->count(),
            'outOfStockCount' => Product::where('quantity', '<=', 0)->count(),
            'codUnavailableCount' => Product::where('cod_available', false)->count(),
            'recentCategories' => Category::withCount('products')->latest()->take(5)->get(),
            'recentOrders' => Order::withCount('items')->latest()->take(5)->get(),
        ]);
    }
}
