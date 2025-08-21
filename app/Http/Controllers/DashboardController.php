<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect admin and manager to admin dashboard
        if ($user->isAdmin() || $user->isManager()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Data untuk dashboard customer
        $totalOrders = Order::where('user_id', $user->id)->count();
        $activeWarranties = Warranty::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('ends_at', '>', now())->where('is_active', true)->count();
        
        $pendingPayments = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        $activeWarrantiesList = Warranty::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('ends_at', '>', now())
          ->where('is_active', true)
          ->latest('ends_at')
          ->take(5)
          ->get();
        
        return view('dashboard', compact(
            'totalOrders', 
            'activeWarranties', 
            'pendingPayments', 
            'recentOrders', 
            'activeWarrantiesList'
        ));
    }
    
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Check if user is admin or manager using gate
        if (!Gate::allows('admin-access')) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke dashboard admin.');
        }
        
        // Data untuk dashboard admin
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $activeWarranties = Warranty::where('ends_at', '>', now())->where('is_active', true)->count();
        
        $recentOrders = Order::latest()->take(5)->get();
        $recentPayments = Payment::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalOrders', 
            'totalRevenue', 
            'activeWarranties', 
            'recentOrders', 
            'recentPayments'
        ));
    }
}
