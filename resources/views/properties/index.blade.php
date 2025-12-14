@extends('layouts.dashboard')

@section('content')
<div style="padding: 40px; max-width: 1200px; margin: auto;">
    <h2 style="margin-bottom: 20px;">Available Properties</h2>

    @if($properties->isEmpty())
        <p>No properties available.</p>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @foreach($properties as $property)
            <div style="background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: space-between; transition: 0.3s;">
                
                {{-- Property Image --}}
                @if($property->images->count())
                    <img src="{{ asset('storage/' . $property->images->first()->path) }}" 
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                @endif

                <h3>{{ $property->title }}</h3>
                <p>{{ $property->description }}</p>
                <p><strong>Price:</strong> {{ $property->price ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ $property->city ?? 'N/A' }}</p>
                <p><strong>Bedrooms:</strong> {{ $property->bedrooms ?? 'N/A' }}</p>
                <p><strong>Bathrooms:</strong> {{ $property->bathrooms ?? 'N/A' }}</p>
                <p><strong>Area:</strong> {{ $property->area ?? 'N/A' }} sqft</p>

                {{-- Owner Delete Button (only for their own property) --}}
                @if(session('active_role') === 'owner' && $property->user_id === Auth::id())
                    <form action="{{ route('property.destroy', $property->id) }}" method="POST" style="margin-top:10px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                @endif

            </div>
        @endforeach
    </div>
</div>

<style>
/* Hover effect for property cards */
div[style*="grid-template-columns"] > div:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
</style>
@endsection
