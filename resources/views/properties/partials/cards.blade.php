@if($properties->isEmpty())
    <p>No properties available.</p>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @foreach($properties as $property)
            @php
                $propertyStatus = $property->status(); // Use method
                $userRole = session('active_role');
                $userId = Auth::id();
                $assignedTenantId = $property->bookingRequests
                    ->where('status', 'active')
                    ->first()?->user_id;
                $rentPaid = \App\Models\RentPayment::where('property_id', $property->id)
                            ->where('tenant_id', $userId)
                            ->exists();
            @endphp

            <div class="property-card"
                 style="
                    position: relative;
                    background:#fff;
                    padding:20px;
                    border-radius:12px;
                    box-shadow:0 4px 12px rgba(0,0,0,0.1);
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    transition:0.3s;
                 ">

                {{-- STATUS BADGE --}}
                <span style="
                    position:absolute;
                    top:12px;
                    right:12px;
                    padding:6px 14px;
                    border-radius:20px;
                    font-size:12px;
                    font-weight:600;
                    color:#fff;
                    background:
                        {{ $propertyStatus === 'active' ? '#2ecc71' : ($propertyStatus === 'pending' ? '#f39c12' : '#e74c3c') }};
                ">
                    {{ ucfirst($propertyStatus) }}
                </span>

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

                    {{-- PAY RENT (Tenant only, inactive property, assigned tenant) --}}
                    @if($userRole === 'tenant' && $propertyStatus === 'inactive' && $assignedTenantId === $userId)
                        <form method="POST" action="{{ route('rent.pay') }}"
                              onsubmit="return confirm('Confirm rent payment?');">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">
                            <button type="submit" class="btn btn-success" @if($rentPaid) disabled @endif>
                                {{ $rentPaid ? 'Rent Paid' : 'Pay Rent (' . $property->price . ' TK)' }}
                            </button>
                        </form>
                    @endif

                    {{-- APPLY (Tenant only, not own property, property active) --}}
                    @if($userRole === 'tenant' && $property->user_id !== $userId)
                        @php
                            $applied = $property->bookingRequests->where('user_id', $userId)->first();
                        @endphp

                        @if(!$applied && $propertyStatus === 'active')
                            <form action="{{ route('booking.apply', $property->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Apply for Tenant
                                </button>
                            </form>
                        @elseif($applied)
                            <button class="btn btn-sm btn-success" disabled>Applied</button>
                        @elseif($propertyStatus === 'inactive')
                            <button class="btn btn-sm btn-secondary" disabled>Closed</button>
                        @endif
                    @endif

                    {{-- DELETE (Owner only) --}}
                    @if($userRole === 'owner' && $property->user_id === $userId)
                        <form action="{{ route('property.destroy', $property->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Delete
                            </button>
                        </form>
                    @endif

                    {{-- Owner viewing own property as tenant --}}
                    @if($userRole === 'tenant' && $property->user_id === $userId)
                        <button class="btn btn-sm btn-secondary" disabled>
                            You own this property
                        </button>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
@endif
