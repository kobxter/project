<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // ตรวจสอบข้อมูล
        $request->validate([
            'phone' => 'required|string|max:15',
        ]);

        // อัปเดตเฉพาะเบอร์โทรศัพท์
        $user = auth()->user();
        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->route('profile')->with('success', 'อัปเดตเบอร์โทรศัพท์สำเร็จ');
    }
}
