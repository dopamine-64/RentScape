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

        // Also assign user_id for owner relationship
        $data['user_id'] = Auth::id();
        
        $data['owner_id'] = Auth::id() ?? 0;
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
    // Compare Properties
    // =========================
    public function compare(Request $request)
    {
        // Get property IDs from query string
        $propertyIds = $request->input('property_ids', []);
        
        // If property_ids is a string (from GET parameters), convert to array
        if (is_string($propertyIds)) {
            $propertyIds = explode(',', $propertyIds);
        }
        
        // Validate: at least 2 properties must be selected
        if (count($propertyIds) < 2) {
            return redirect()->route('properties.index')
                ->with('error', 'Please select at least 2 properties to compare.');
        }
        
        // Limit to maximum 5 properties for comparison
        if (count($propertyIds) > 5) {
            $propertyIds = array_slice($propertyIds, 0, 5);
            session()->flash('info', 'Maximum 5 properties can be compared. Showing first 5.');
        }
        
        // Fetch the selected properties with their images
        $properties = Property::with(['images', 'user'])
            ->whereIn('id', $propertyIds)
            ->get();
        
        // Check if all properties exist
        if ($properties->count() !== count($propertyIds)) {
            return redirect()->route('properties.index')
                ->with('error', 'One or more selected properties were not found.');
        }
        
        // Calculate statistics for comparison
        $prices = $properties->pluck('price')->filter()->toArray();
        $averagePrice = count($prices) > 0 ? array_sum($prices) / count($prices) : 0;
        $minPrice = count($prices) > 0 ? min($prices) : 0;
        $maxPrice = count($prices) > 0 ? max($prices) : 0;
        
        return view('properties.compare', compact(
            'properties', 
            'averagePrice', 
            'minPrice', 
            'maxPrice'
        ));
    }

    // =========================
    // Show Single Property
    // =========================
    public function show($id)
    {
        $property = Property::with(['images', 'user', 'bookingRequests'])
            ->findOrFail($id);
        
        return view('properties.show', compact('property'));
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
