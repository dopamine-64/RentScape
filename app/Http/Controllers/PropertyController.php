<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    // Show the form
    public function create()
    {
        return view('properties.create');
    }

    // Handle form submission
    public function store(Request $request)
    {
        // Validate the form
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
            'images.*' => 'nullable|image|max:5120' // max 5MB each
        ]);

        // Assign a user id (optional if auth exists)
        $data['user_id'] = Auth::id() ?? null;

        // Create property
        $property = Property::create($data);

        // Save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("properties/{$property->id}", 'public');
                $property->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Property posted successfully!');
    }
}
