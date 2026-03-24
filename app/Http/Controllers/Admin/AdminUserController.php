<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->orderBy('name')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.form', ['admin' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'تم إضافة المدير بنجاح');
    }

    public function edit(User $admin)
    {
        abort_if(!$admin->is_admin, 404);
        return view('admin.admins.form', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'password' => ['nullable', Password::min(8)],
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'تم تحديث بيانات المدير');
    }

    public function destroy(User $admin)
    {
        abort_if(!$admin->is_admin, 404);

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'تم حذف المدير');
    }
}
