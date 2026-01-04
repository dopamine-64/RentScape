<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Wishlist;
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
    // SHOW ALL PROPERTIES (Everyone)
    // =========================
    public function index(Request $request)
    {
        $query = Property::with([
            'images',
            'bookingRequests' => function ($q) {
                if (Auth::check()) {
                    $q->where('user_id', Auth::id());
                }
            }
        ]);

        // 🔍 Case-insensitive smart search
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

       


        // ❤️ LOAD WISHLISTED PROPERTY IDS (FOR HEART TOGGLE)
        $wishlistedIds = [];
        if (Auth::check() && Auth::user()->role === 'tenant') {
            $wishlistedIds = Wishlist::where('user_id', Auth::id())
                ->pluck('property_id')
                ->toArray();
        }

        // ⚡ AJAX response
        if ($request->ajax()) {
            return view('properties.partials.cards', compact('properties', 'wishlistedIds'))->render();
        }

        return view('properties.index', compact('properties', 'wishlistedIds'));

    }

    // =========================
    // DELETE PROPERTY (Owner Only)
    // =========================
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->delete();

        return back()->with('success', 'Property deleted successfully!');
    }
}
