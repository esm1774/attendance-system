<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        // الإحصائيات الأساسية
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'total_roles' => Role::count(),
            'active_roles' => Role::active()->count(),
            'total_permissions' => Permission::count(),
            'admins_count' => User::whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })->count(),
            'teachers_count' => User::whereHas('role', function ($query) {
                $query->where('name', 'teacher');
            })->count(),
        ];

        // آخر المستخدمين المسجلين
        $recentUsers = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        // الأدوار مع عدد المستخدمين
        $rolesWithUsers = Role::withCount('users')
            ->active()
            ->get();

        return view('dashboard', compact('stats', 'recentUsers', 'rolesWithUsers'));
    }

    /**
     * الحصول على إحصائيات للرسوم البيانية (للاستخدام المستقبلي)
     */
    public function getChartData()
    {
        $usersByRole = Role::withCount('users')->get();
        
        $data = [
            'labels' => $usersByRole->pluck('name_ar'),
            'data' => $usersByRole->pluck('users_count'),
            'colors' => ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1']
        ];

        return response()->json($data);
    }
}