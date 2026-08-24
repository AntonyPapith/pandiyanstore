<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile', ['admin' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$admin->id],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,'.$admin->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->phone = filled($data['phone'] ?? null) ? $data['phone'] : null;
        if (filled($data['password'] ?? null)) {
            $admin->password = $data['password'];
        }
        $admin->saveOrFail();

        return back()->with('success', 'Admin details updated successfully.');
    }
}
