<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    // =========================
    // Show Create Property Form
    // =========================
    public function create()
    {
        return view('properties.create');
    }

    // =========================
    // Store Property (Owner)
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'nullable|numeric',
            'address'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:255',
            'neighborhood'   => 'nullable|string',
            'property_type'  => 'nullable|string',
            'bedrooms'       => 'nullable|integer',
            'bathrooms'      => 'nullable|integer',
            'area'           => 'nullable|integer',
            'contact_email'  => 'nullable|string',
            'contact_phone'  => 'nullable|string',
            'images.*'       => 'nullable|image|max:5120',
        ]);

        // Always pass 0 for owner_id to bypass the NOT NULL constraint
        $data['owner_id'] = 0;

        // Also assign user_id for owner relationship
        $data['user_id'] = Auth::id();

        $property = Property::create($data);

        // Store images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("properties/{$property->id}", 'public');
                $property->images()->create(['path' => $path]);
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Property posted successfully!');
    }

    // =========================
    // Show All Properties (Everyone)
    // =========================
    public function index(Request $request)
    {
        $query = Property::with(['images', 'bookingRequests']);

        // Search query
        if ($request->filled('q')) {
            $search = strtolower($request->q);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(city) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(address) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(property_type) LIKE ?', ["%{$search}%"]);
            });
        }

        $properties = $query->latest()->get();

        // If AJAX request (smart search), return only partial cards
        if ($request->ajax()) {
            return view('properties.partials.cards', compact('properties'))->render();
        }

        return view('properties.index', compact('properties'));
    }

    // =========================
    // Delete Property (Owner Only)
    // =========================
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        // Only owner can delete
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete property (images will remain in storage; optionally delete them manually)
        $property->delete();

        return back()->with('success', 'Property deleted successfully!');
    }
}
