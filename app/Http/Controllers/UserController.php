<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'role' => 'required|in:admin,staff,user',
        ]);

        User::create([
            'username' => strtolower($validatedData['first_name'] . '.' . $validatedData['last_name']),
            'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
            'department' => $validatedData['department'],
            'title' => $validatedData['title'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'nickname' => $validatedData['nickname'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'],
            'position' => $validatedData['position'],
            'role' => $validatedData['role'], // Assign role
            'status' => 'ปกติ', // กำหนดค่าเริ่มต้นเป็น 'ปกติ'
        ]);
        return redirect()->route('users.index')->with('success', 'เพิ่มผู้ใช้สำเร็จ');
    }
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'department' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'role' => 'required|in:admin,staff,user',
        ]);

        $user->update([
            'department' => $validatedData['department'],
            'title' => $validatedData['title'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'nickname' => $validatedData['nickname'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $user->password,
            'phone' => $validatedData['phone'],
            'position' => $validatedData['position'],
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้สำเร็จ');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'ลบผู้ใช้สำเร็จ');
    }
}
