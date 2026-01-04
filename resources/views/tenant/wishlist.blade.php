@extends('layouts.app')

@section('content')
<div class="container">
    <h2>❤️ My Wishlist</h2>

    @forelse($wishlists as $wishlist)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $wishlist->property->title }}</h5>
                <p>{{ $wishlist->property->city }}</p>
                <p>৳ {{ $wishlist->property->price }}</p>

                <form method="POST" action="{{ route('wishlist.toggle', $wishlist->property) }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Remove</button>
                </form>
            </div>
        </div>
    @empty
        <p>No properties in wishlist.</p>
    @endforelse
</div>
@endsection
