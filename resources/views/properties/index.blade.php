@extends('layouts.app')

@section('content')
<div style="padding: 40px; max-width: 1200px; margin: auto;">

    {{-- Alerts --}}
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

    {{-- Search --}}
    <div class="search-wrapper">
        <i class="ri-search-line"></i>
        <input
            type="text"
            id="smartSearch"
            class="smart-search"
            placeholder="Search by title, city, address or type">
    </div>

    {{-- Property Results --}}
    <div id="propertyResults">
        @include('properties.partials.cards', ['properties' => $properties])
    </div>
</div>

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
}
</style>

<script>
// 🔍 Live Search
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

// ❤️ Wishlist Toggle (AJAX)
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.wishlist-btn');
    if (!btn) return;

    const propertyId = btn.dataset.propertyId;

    fetch(`/wishlist/toggle/${propertyId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        const icon = btn.querySelector('i');
        if (data.status === 'added') {
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill');
        } else {
            icon.classList.remove('ri-heart-fill');
            icon.classList.add('ri-heart-line');
        }
    });
});
</script>
@endsection
<script>
document.addEventListener('click', function (e) {
    if (!e.target.closest('.wishlist-btn')) return;

    const btn = e.target.closest('.wishlist-btn');
    const propertyId = btn.dataset.id;
    const icon = btn.querySelector('.heart-icon');

    fetch(`/wishlist/toggle/${propertyId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        icon.textContent = data.status === 'added' ? '❤️' : '🤍';
    });
});
</script>
