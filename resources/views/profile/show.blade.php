@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: auto; padding: 40px;">

    <h2>My Profile</h2>

    @if(session('success'))
        <div style="background: #d4edda; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 20px;">
        <div>
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" style="width:150px; height:150px; object-fit:cover; border-radius:50%;">
            @else
                <img src="https://via.placeholder.com/150" style="border-radius:50%;">
            @endif
        </div>
        <div>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Bio:</strong> {{ $user->bio ?? '-' }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>

</div>
@endsection

