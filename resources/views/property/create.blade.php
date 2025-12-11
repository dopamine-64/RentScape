@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f5f7fa;
    }

    .card {
        background: linear-gradient(135deg, #ffffff, #f0f4f8);
        border-radius: 15px;
    }

    .form-card {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }

    h5.section-title {
        font-weight: 700;
        color: #222;
        margin-bottom: 15px;
        border-bottom: 2px solid #FF6B6B;
        padding-bottom: 5px;
    }

    .form-label {
        font-weight: 600;
    }

    input.form-control:focus, textarea.form-control:focus, select.form-control:focus {
        border-color: #FF6B6B;
        box-shadow: 0 0 5px rgba(255,107,107,0.3);
    }

    .btn-submit {
        background: linear-gradient(90deg, #FF6B6B, #FFB56B);
        color: white;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 10px;
        transition: transform 0.2s;
    }

    .btn-submit:hover {
        transform: scale(1.05);
    }

    #image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    #image-preview .img-wrapper {
        position: relative;
        display: inline-block;
    }

    #image-preview img {
        height: 100px;
        width: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .remove-image {
        position: absolute;
        top: 0;
        right: 0;
        background: rgba(0,0,0,0.6);
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        cursor: pointer;
        font-weight: bold;
    }

    /* Vibrant Post Property header */
    .header-gradient {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        color: #FF6B6B; 
        letter-spacing: 1px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        margin-bottom: 2rem;
    }

</style>

<div class="container mt-5">
    <div class="card shadow-lg rounded p-5">

        <h2 class="header-gradient">Post Property</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('property.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Section 1: Basic Info -->
            <div class="form-card">
                <h5 class="section-title">Basic Info</h5>
                <div class="mb-3">
                    <label class="form-label"><i class="ri-file-text-line"></i> Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Cozy 2-Bedroom Apartment in Downtown" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="ri-chat-1-line"></i> Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe your property"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="ri-money-dollar-circle-line"></i> Price (BDT)</label>
                        <input type="number" name="price" class="form-control" placeholder="Monthly rent">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="ri-bed-line"></i> Bedrooms</label>
                        <input type="number" name="bedrooms" class="form-control" placeholder="Number of bedrooms">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="ri-shower-line"></i> Bathrooms</label>
                        <input type="number" name="bathrooms" class="form-control" placeholder="Number of bathrooms">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"><i class="ri-arrows-expand-line"></i> Area (sq ft)</label>
                        <input type="number" name="area" class="form-control" placeholder="Square feet">
                    </div>
                </div>
            </div>

            <!-- Section 2: Location & Type -->
            <div class="form-card">
                <h5 class="section-title">Location & Type</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-map-pin-line"></i> Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Street address">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-map-pin-line"></i> City</label>
                        <input type="text" name="city" class="form-control" placeholder="City name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-map-pin-line"></i> Neighborhood</label>
                        <input type="text" name="neighborhood" class="form-control" placeholder="e.g., Gulshan, Dhanmondi">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-home-smile-line"></i> Property Type</label>
                        <select name="property_type" class="form-control">
                            <option value="" selected disabled>Select Type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="condo">Condo</option>
                            <option value="studio">Studio</option>
                            <option value="villa">Villa</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Rental Details -->
            <div class="form-card">
                <h5 class="section-title">Rental Details</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="ri-calendar-line"></i> Available Date</label>
                        <input type="date" name="available_date" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="ri-time-line"></i> Lease Term</label>
                        <select name="lease_term" class="form-control">
                            <option value="" selected disabled>Select Term</option>
                            <option value="6 months">6 months</option>
                            <option value="12 months">12 months</option>
                            <option value="month-to-month">Month-to-Month</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><i class="ri-shield-check-line"></i> Security Deposit (BDT)</label>
                        <input type="number" name="security_deposit" class="form-control" placeholder="Optional">
                    </div>
                </div>
            </div>

            <!-- Section 4: Amenities & Policies -->
            <div class="form-card">
                <h5 class="section-title">Amenities & Policies</h5>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="parking" value="1" class="form-check-input" id="parkingCheck">
                        <label class="form-check-label" for="parkingCheck">Includes Parking</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Laundry</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="laundry" value="in-unit">
                            <label class="form-check-label">In-unit</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="laundry" value="building">
                            <label class="form-check-label">Building</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="laundry" value="none">
                            <label class="form-check-label">None</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pet Policy</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pet_policy" value="pet-friendly">
                            <label class="form-check-label">Pet-friendly <span>🐶</span></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pet_policy" value="no-pets">
                            <label class="form-check-label">No pets</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="furnished" value="1" class="form-check-input" id="furnishedCheck">
                    <label class="form-check-label" for="furnishedCheck">Furnished</label>
                </div>
            </div>

            <!-- Section 5: Contact & Media -->
            <div class="form-card">
                <h5 class="section-title">Contact & Media</h5>
                <div class="mb-3">
                    <label class="form-label"><i class="ri-image-line"></i> Images (multiple)</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Upload multiple images (jpg, png, jpeg, gif)</small>
                    <div id="image-preview" class="d-flex flex-wrap mt-3"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-mail-line"></i> Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" placeholder="Your contact email">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="ri-phone-line"></i> Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="Phone number">
                    </div>
                </div>
            </div>

            <button class="btn-submit w-100 mt-3 py-2" type="submit">Submit Property</button>
        </form>
    </div>
</div>

<!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<script>
    const inputImages = document.querySelector('input[name="images[]"]');
    const previewDiv = document.getElementById('image-preview');

    inputImages.addEventListener('change', function() {
        previewDiv.innerHTML = '';
        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('img-wrapper');

                const img = document.createElement('img');
                img.src = e.target.result;

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = '×';
                removeBtn.classList.add('remove-image');
                removeBtn.onclick = function() {
                    wrapper.remove();
                }

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                previewDiv.appendChild(wrapper);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
