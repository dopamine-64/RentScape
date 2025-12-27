@if($properties->isEmpty())
    <p>No properties available.</p>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @foreach($properties as $property)
            <div class="property-card"
                 style="
                    background:#fff;
                    padding:20px;
                    border-radius:12px;
                    box-shadow:0 4px 12px rgba(0,0,0,0.1);
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    transition:0.3s;
                 ">

                {{-- Property Image --}}
                @if($property->images->count())
                    <img src="{{ asset('storage/' . $property->images->first()->path) }}"
                         style="width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                @endif

                <h3>{{ $property->title }}</h3>
                <p>{{ $property->description }}</p>

                <p><strong>Price:</strong> {{ $property->price ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $property->address ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ $property->city ?? 'N/A' }}</p>
                <p><strong>Type:</strong> {{ $property->property_type ?? 'N/A' }}</p>
                <p><strong>Area:</strong> {{ $property->area ?? 'N/A' }} sqft</p>

                {{-- ACTION BUTTONS --}}
                <div style="margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap;">

                    {{-- APPLY (Tenant only, not own property) --}}
                    @if(session('active_role') === 'tenant' && $property->user_id !== Auth::id())
                        @php
                            $applied = $property->bookingRequests->where('user_id', Auth::id())->first();
                        @endphp

                        @if($applied)
                            <button class="btn btn-sm btn-success" disabled>Applied</button>
                        @else
                            <form action="{{ route('booking.apply', $property->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Apply for Tenant
                                </button>
                            </form>
                        @endif
                    @endif

                    {{-- DELETE (Owner only) --}}
                    @if(session('active_role') === 'owner' && $property->user_id === Auth::id())
                        <form action="{{ route('property.destroy', $property->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
@endif