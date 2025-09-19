<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $admins = $query->latest()->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(StoreAdminRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        $admin->load(['createdPages' => function($query) {
            $query->latest()->limit(5);
        }]);
        
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        // Prevent deletion of current admin
        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->route('admin.admins.index')
                            ->with('error', 'You cannot delete your own account.');
        }

        // Check if admin has created pages
        $pagesCount = $admin->createdPages()->count();
        if ($pagesCount > 0) {
            return redirect()->route('admin.admins.index')
                            ->with('error', "Cannot delete admin who has created {$pagesCount} pages.");
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Admin deleted successfully.');
    }

    public function changePassword(ChangePasswordRequest $request, Admin $admin)
    {
        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.admins.index')
                        ->with('success', 'Password changed successfully.');
    }

    public function editProfile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $admin = Auth::guard('admin')->user();

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function showChangePassword()
    {
        return view('admin.profile.change-password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $admin = Auth::guard('admin')->user();

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.change-password')->with('success', 'Password changed successfully.');
    }
}