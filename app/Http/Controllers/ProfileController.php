<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        // No update logic needed for Component-2
        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        // Not needed for Component-2
        return back();
    }
}
