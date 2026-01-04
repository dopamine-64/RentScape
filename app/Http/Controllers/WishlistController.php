<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Property;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('property')
            ->where('user_id', auth()->id())
            ->get();

        return view('tenant.wishlist', compact('wishlists'));
    }

    public function toggle(Property $property)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('property_id', $property->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'property_id' => $property->id,
        ]);

        return response()->json(['status' => 'added']);
    }
}
