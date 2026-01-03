<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            
            if ($user->profile_image && Storage::exists($user->profile_image)) {
                Storage::delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
