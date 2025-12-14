<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    // Show the form for creating a property
    public function create()
    {
        return view('properties.create');
    }

    // Handle form submission and save property
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'nullable|integer',
            'images.*' => 'nullable|image|max:5120'
        ]);

        $data['user_id'] = Auth::id() ?? null;

        $property = Property::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("properties/{$property->id}", 'public');
                $property->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Property posted successfully!');
    }

    // ✅ THIS IS THE INDEX METHOD
    public function index()
    {
        $role = session('active_role', Auth::user()->role);

        if($role === 'owner') {
            // Owner sees only their properties
            $properties = Property::where('user_id', Auth::id())->latest()->get();
        } else {
            // Tenant sees all properties
            $properties = Property::latest()->get();
        }

        return view('properties.index', compact('properties'));
    }

    // Optionally, destroy method for owner to delete their own property
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        if($property->user_id !== Auth::id()) {
            abort(403);
        }

        $property->delete();
        return back()->with('success', 'Property deleted successfully!');
    }
}
