@extends('layouts.app')

@section('content')
<div style="padding: 40px; max-width: 1200px; margin: auto;">

    {{-- ✅ ALERT MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-box">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-box">
            {{ session('error') }}
        </div>
    @endif

    <h2 style="margin-bottom: 20px;">Available Properties</h2>

    {{-- Search Bar --}}
    <div class="search-wrapper">
        <i class="ri-search-line"></i>
        <input
            type="text"
            id="smartSearch"
            class="smart-search"
            placeholder="Search by title, city, address or type (e.g. Kalabagan, Lakecircus, Apartment)">
    </div>

    {{-- Property Results --}}
    <div id="propertyResults">
        @if($properties->isEmpty())
            <p>No properties available.</p>
        @else
            <div class="property-grid">
                @foreach($properties as $property)
                    <div class="property-card">

                        {{-- Property Image --}}
                        @if($property->images->count())
                            <img src="{{ asset('storage/' . $property->images->first()->path) }}"
                                 class="property-image">
                        @endif

                        <h3>{{ $property->title }}</h3>
                        <p>{{ $property->description }}</p>
                        <p><strong>Price:</strong> {{ $property->price ?? 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $property->address ?? 'N/A' }}</p>
                        <p><strong>City:</strong> {{ $property->city ?? 'N/A' }}</p>
                        <p><strong>Type:</strong> {{ $property->property_type ?? 'N/A' }}</p>
                        <p><strong>Area:</strong> {{ $property->area ?? 'N/A' }} sqft</p>

                        {{-- Owner Delete Button --}}
                        @if(session('active_role') === 'owner' && $property->user_id === Auth::id())
                            <form action="{{ route('property.destroy', $property->id) }}" method="POST" style="margin-top: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endif

                        {{-- Tenant Apply Button --}}
                        @if(session('active_role') === 'tenant' && $property->user_id !== Auth::id())
                            @php
                                $applied = $property->bookingRequests()
                                    ->where('user_id', Auth::id())
                                    ->exists();
                            @endphp

                            <form action="{{ route('booking.apply', $property->id) }}" method="POST" style="margin-top: 10px;">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-primary"
                                        {{ $applied ? 'disabled' : '' }}>
                                    {{ $applied ? 'Applied' : 'Apply for Tenant' }}
                                </button>
                            </form>
                        @endif

                        {{-- Owner viewing own property as tenant --}}
                        @if(session('active_role') === 'tenant' && $property->user_id === Auth::id())
                            <button class="btn btn-sm btn-secondary" disabled style="margin-top: 10px;">
                                You own this property
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Remix Icons --}}
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<style>
.alert-box {
    padding: 12px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    animation: fadeOut 4s forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    70% { opacity: 1; }
    100% { opacity: 0; display: none; }
}

.search-wrapper {
    position: relative;
    margin-bottom: 30px;
}

.search-wrapper i {
    position: absolute;
    top: 50%;
    left: 18px;
    transform: translateY(-50%);
    color: #666;
    font-size: 18px;
}

.smart-search {
    width: 100%;
    padding: 14px 18px 14px 46px;
    font-size: 16px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    outline: none;
    transition: all 0.3s ease;
}

.property-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.property-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.property-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.property-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}
</style>

<script>
const searchInput = document.getElementById('smartSearch');
let typingTimer = null;

searchInput.addEventListener('keyup', function () {
    clearTimeout(typingTimer);

    typingTimer = setTimeout(() => {
        fetch(`{{ route('properties.index') }}?q=${encodeURIComponent(this.value)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('propertyResults').innerHTML = html;
        });
    }, 300);
});
</script>
@endsection
