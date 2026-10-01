<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MasterAdminController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        $admins = User::where('role', 'admin')->get();

        return view('master.dashboard', compact('totalAdmins', 'recentLogs', 'admins'));
    }

    public function createAdmin()
    {
        return view('master.admin.create');
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'username' => explode('@', $validated['email'])[0] . '_' . rand(100, 999),
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '-', 
        ]);

        return redirect()->route('master.dashboard')->with('success', 'Akun admin seni berhasil dibuat.');
    }

    public function toggleStatus($id)
    {
        $admin = User::findOrFail($id);
        if ($admin->role === 'master') {
            return redirect()->back()->withErrors(['error' => 'Tidak dapat mengubah status Master Admin.']);
        }

        $admin->status = $admin->status === 'active' ? 'suspended' : 'active';
        $admin->save();

        return redirect()->back()->with('success', 'Status admin berhasil diperbarui menjadi ' . $admin->status);
    }

    public function viewLogs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(15);
        return view('master.logs', compact('logs'));
    }
}
