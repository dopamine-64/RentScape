@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(12px);">
                <div class="card-header" style="background: rgba(255, 107, 107, 0.9); color: #fff; border-bottom: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>View Applicants
                        </h4>
                        <span class="badge" style="background-color: rgba(255, 255, 255, 0.9); color: #FF6B6B;">
                            {{ count($bookingRequests) }} Total
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" style="background: rgba(40, 167, 69, 0.9); color: white; border: none;">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" style="background: rgba(220, 53, 69, 0.9); color: white; border: none;">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($bookingRequests->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x" style="color: rgba(255, 107, 107, 0.5);"></i>
                            <h4 style="color: #333;">No applicants yet</h4>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($bookingRequests as $request)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px);">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px; font-size: 1.2rem; background: linear-gradient(135deg, #FF6B6B, #FF8E53); color: white;">
                                                {{ substr($request->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0" style="color: #333;">{{ $request->user->name }}</h6>
                                                <small class="text-muted">{{ $request->user->email }}</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <h6 style="color: #FF6B6B;">{{ $request->property->title }}</h6>
                                            
                                            <!-- Property Status Display -->
                                            <div class="mb-2">
                                                <small class="fw-bold" style="color: #333;">
                                                    Property Status: 
                                                    <span class="badge rounded-pill" 
                                                          style="@if($request->status == 'active') background: rgba(108, 117, 125, 0.9); color: white; 
                                                                 @else background: rgba(40, 167, 69, 0.9); color: white; @endif">
                                                        @if($request->status == 'active')
                                                            Inactive
                                                        @else
                                                            Active
                                                        @endif
                                                    </span>
                                                </small>
                                            </div>
                                            
                                            <p class="text-muted small mb-1">
                                                <i class="fas fa-map-marker-alt me-1" style="color: #FF6B6B;"></i>
                                                {{ $request->property->address }}
                                            </p>
                                        </div>

                                        <!-- Hide badge for selected tenants (status == 'active') -->
                                        @if($request->status != 'active')
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge rounded-pill" 
                                                  style="@if($request->status == 'pending') background: rgba(255, 193, 7, 0.9); color: #212529;
                                                         @else background: rgba(108, 117, 125, 0.9); @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $request->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        @else
                                        <!-- Only show date for selected tenants (no badge) -->
                                        <div class="d-flex justify-content-end">
                                            <small class="text-muted">
                                                {{ $request->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        @endif

                                        <div class="mt-3">
                                            @if($request->status == 'pending')
                                                @php
                                                    // Check if this tenant is already selected for ANY other property
                                                    $tenantAlreadySelected = \App\Models\BookingRequest::where('user_id', $request->user_id)
                                                        ->where('status', 'active')
                                                        ->where('id', '!=', $request->id)
                                                        ->exists();
                                                @endphp
                                                
                                                @if($tenantAlreadySelected)
                                                    <div class="p-2 text-center mb-0 rounded" 
                                                         style="background: rgba(108, 117, 125, 0.15); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.3);">
                                                        <i class="fas fa-user-times me-1"></i>Unavailable (Selected Elsewhere)
                                                    </div>
                                                @else
                                                    <form action="{{ route('booking.selectTenant', $request->property_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="booking_request_id" value="{{ $request->id }}">
                                                        <button type="submit" class="btn w-100 border-0" 
                                                                style="background: linear-gradient(135deg, #FF6B6B, #FF8E53); color: #fff; transition: all 0.3s;">
                                                            <i class="fas fa-check me-1"></i>Select Tenant
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($request->status == 'active')
                                                <div class="p-2 text-center mb-0 rounded" 
                                                     style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3);">
                                                    <i class="fas fa-check-circle me-1"></i>Selected Tenant
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookingRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Optional Bootstrap tab handling
    const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function (triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
});
</script>
@endpush

<style>
.avatar {
    font-weight: bold;
}
.card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-radius: 15px;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.15);
}
.btn:hover {
    transform: scale(1.02);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}
</style>
@endsection
