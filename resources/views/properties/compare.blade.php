@extends('layouts.app')

@section('content')
<div style="padding: 40px; max-width: 1400px; margin: auto; background: #f8fafc; min-height: 100vh;">

    {{-- Page Header --}}
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 36px; font-weight: 800; color: #1e3a8a; margin-bottom: 10px;">
            📊 Property Comparison
        </h1>
        <p style="color: #64748b; font-size: 16px; max-width: 600px; margin: 0 auto;">
            Compare properties side by side to make an informed decision
        </p>
    </div>

    {{-- Stats Dashboard --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); text-align: center; border-top: 4px solid #3b82f6;">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">AVERAGE PRICE</div>
            <div style="font-size: 32px; font-weight: 800; color: #1e3a8a; margin-bottom: 5px;">
                {{ number_format($averagePrice) }} TK
            </div>
            <div style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 4px 10px; border-radius: 20px; display: inline-block;">
                Market Average
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); text-align: center; border-top: 4px solid #10b981;">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">MINIMUM PRICE</div>
            <div style="font-size: 32px; font-weight: 800; color: #059669; margin-bottom: 5px;">
                {{ number_format($minPrice) }} TK
            </div>
            <div style="font-size: 12px; color: #f59e0b; background: #fef3c7; padding: 4px 10px; border-radius: 20px; display: inline-block;">
                Best Value
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); text-align: center; border-top: 4px solid #ef4444;">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">MAXIMUM PRICE</div>
            <div style="font-size: 32px; font-weight: 800; color: #dc2626; margin-bottom: 5px;">
                {{ number_format($maxPrice) }} TK
            </div>
            <div style="font-size: 12px; color: #8b5cf6; background: #ede9fe; padding: 4px 10px; border-radius: 20px; display: inline-block;">
                Premium Option
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); text-align: center; border-top: 4px solid #8b5cf6;">
            <div style="font-size: 14px; color: #64748b; margin-bottom: 8px; font-weight: 600;">PROPERTIES COMPARED</div>
            <div style="font-size: 32px; font-weight: 800; color: #7c3aed; margin-bottom: 5px;">
                {{ $properties->count() }}
            </div>
            <div style="font-size: 12px; color: #3b82f6; background: #dbeafe; padding: 4px 10px; border-radius: 20px; display: inline-block;">
                Selection Size
            </div>
        </div>
    </div>

    {{-- Property Comparison Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-bottom: 50px;">
        @foreach($properties as $index => $property)
        <div style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, box-shadow 0.3s ease;">
            <div style="position: relative;">
                {{-- Property Image --}}
                @if($property->images->count())
                    <img src="{{ asset('storage/' . $property->images->first()->path) }}" 
                         style="width: 100%; height: 220px; object-fit: cover; border-bottom: 1px solid #e5e7eb;">
                @else
                    <div style="width: 100%; height: 220px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #e5e7eb;">
                        <span style="font-size: 48px; color: white;">🏠</span>
                    </div>
                @endif
                
                {{-- Price Tag --}}
                <div style="position: absolute; top: 20px; right: 20px; background: rgba(255, 255, 255, 0.95); padding: 10px 18px; border-radius: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <div style="font-size: 22px; font-weight: 800; color: #1e3a8a;">
                        {{ number_format($property->price) }} TK
                    </div>
                </div>

                {{-- Property Status --}}
                @php
                    $propertyStatus = $property->status ?? 'active';
                    $statusConfig = [
                        'active' => ['color' => '#10b981', 'bg' => '#d1fae5', 'label' => 'Available'],
                        'pending' => ['color' => '#f59e0b', 'bg' => '#fef3c7', 'label' => 'Pending'],
                        'inactive' => ['color' => '#ef4444', 'bg' => '#fee2e2', 'label' => 'Rented'],
                    ];
                    $status = $statusConfig[$propertyStatus] ?? $statusConfig['active'];
                @endphp
                <div style="position: absolute; top: 20px; left: 20px;">
                    <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                        {{ $status['label'] }}
                    </span>
                </div>
            </div>

            {{-- Property Content --}}
            <div style="padding: 25px;">
                {{-- Title --}}
                <h3 style="font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 15px; line-height: 1.3;">
                    {{ $property->title }}
                    @if(str_contains(strtolower($property->title), 'beach'))
                        <span style="font-size: 16px; color: #f59e0b;">🏖️</span>
                    @endif
                </h3>

                {{-- Description --}}
                <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 20px; min-height: 60px;">
                    {{ Str::limit($property->description ?? 'No description available', 120) }}
                </p>

                {{-- Property Details Grid --}}
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 36px; height: 36px; background: #e0f2fe; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 18px;">📍</span>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #64748b;">Address</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ Str::limit($property->address ?? 'N/A', 25) }}</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 36px; height: 36px; background: #f0f9ff; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 18px;">🏙️</span>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #64748b;">City</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $property->city ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 36px; height: 36px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 18px;">🏠</span>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #64748b;">Type</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $property->property_type ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 36px; height: 36px; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 18px;">📐</span>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #64748b;">Area</div>
                            <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $property->area ?? 'N/A' }} sqft</div>
                        </div>
                    </div>
                </div>

                {{-- Additional Details --}}
                <div style="background: #f8fafc; border-radius: 12px; padding: 15px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-around; text-align: center;">
                        <div>
                            <div style="font-size: 20px; font-weight: 700; color: #3b82f6;">{{ $property->bedrooms ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #64748b;">Bedrooms</div>
                        </div>
                        <div style="border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; padding: 0 25px;">
                            <div style="font-size: 20px; font-weight: 700; color: #10b981;">{{ $property->bathrooms ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #64748b;">Bathrooms</div>
                        </div>
                        <div>
                            @php
                                $pricePerSqft = $property->area > 0 ? $property->price / $property->area : 0;
                            @endphp
                            <div style="font-size: 20px; font-weight: 700; color: #8b5cf6;">{{ number_format($pricePerSqft, 2) }}</div>
                            <div style="font-size: 12px; color: #64748b;">TK/sqft</div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons REMOVED --}}
                {{-- <div style="display: flex; gap: 10px;">
                    <a href="{{ route('property.show', $property->id) }}" 
                       style="flex: 1; background: #3b82f6; color: white; text-align: center; padding: 12px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: background 0.3s;">
                        👁️ View Details
                    </a>
                    @if(Auth::check() && (session('active_role') === 'tenant' || session('active_role') === 'owner'))
                    <form method="POST" action="{{ route('booking.apply', $property->id) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" 
                                style="width: 100%; background: #10b981; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: background 0.3s;">
                            📝 Apply Now
                        </button>
                    </form>
                    @endif
                </div> --}}
            </div>

            {{-- Price Comparison Indicator --}}
            @php
                $priceDiff = $property->price - $averagePrice;
                $percentDiff = $averagePrice > 0 ? ($priceDiff / $averagePrice) * 100 : 0;
            @endphp
            <div style="padding: 15px 25px; background: #f8fafc; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 13px; color: #64748b;">
                    Compared to average:
                </div>
                <div style="font-size: 15px; font-weight: 600; 
                    @if($percentDiff > 0) color: #ef4444; background: #fee2e2; padding: 4px 12px; border-radius: 20px; @
                    elseif($percentDiff < 0) color: #10b981; background: #d1fae5; padding: 4px 12px; border-radius: 20px; @
                    else color: #6b7280; background: #f3f4f6; padding: 4px 12px; border-radius: 20px; @endif">
                    @if($percentDiff > 0)
                        ↑ {{ number_format(abs($percentDiff), 1) }}% above
                    @elseif($percentDiff < 0)
                        ↓ {{ number_format(abs($percentDiff), 1) }}% below
                    @else
                        At average
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Comparison Summary --}}
    @if($properties->count() >= 2)
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); margin-top: 30px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">📈</span> Price Analysis
        </h3>
        
        <div style="display: flex; align-items: flex-end; height: 200px; gap: 30px; padding: 20px; background: #f8fafc; border-radius: 12px; margin-bottom: 20px;">
            @foreach($properties as $property)
                @php
                    $maxBarHeight = 150;
                    $priceRatio = $maxPrice > 0 ? ($property->price / $maxPrice) : 0;
                    $barHeight = $maxBarHeight * $priceRatio;
                    $isHighest = $property->price == $maxPrice;
                    $isLowest = $property->price == $minPrice;
                @endphp
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center;">
                    <div style="height: {{ $barHeight }}px; width: 40px; 
                        background: {{ $isHighest ? '#10b981' : ($isLowest ? '#3b82f6' : '#8b5cf6') }};
                        border-radius: 8px 8px 0 0; position: relative;">
                        <div style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); font-size: 14px; font-weight: 700; white-space: nowrap; background: white; padding: 4px 8px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            {{ number_format($property->price) }} TK
                        </div>
                    </div>
                    <div style="margin-top: 15px; text-align: center;">
                        <div style="font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 5px;">
                            {{ Str::limit($property->title, 15) }}
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                            {{ $property->city ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 14px; color: #64748b; padding: 0 20px;">
            <div style="text-align: center;">
                <div style="font-size: 16px; font-weight: 700; color: #3b82f6;">{{ number_format($minPrice) }} TK</div>
                <div>Lowest</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 16px; font-weight: 700; color: #8b5cf6;">{{ number_format($averagePrice) }} TK</div>
                <div>Average</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 16px; font-weight: 700; color: #10b981;">{{ number_format($maxPrice) }} TK</div>
                <div>Highest</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Empty State --}}
    @if($properties->count() == 0)
    <div style="text-align: center; padding: 60px 20px;">
        <div style="font-size: 64px; margin-bottom: 20px;">🏠</div>
        <h3 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">
            No Properties to Compare
        </h3>
        <p style="color: #64748b; max-width: 500px; margin: 0 auto 30px;">
            Select properties to compare their features, prices, and availability side by side.
        </p>
        <a href="{{ route('properties.index') }}" 
           style="display: inline-block; background: #3b82f6; color: white; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: 600;">
            Browse Properties
        </a>
    </div>
    @endif

</div>

<style>
    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .property-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-dashboard {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-dashboard {
            grid-template-columns: 1fr;
        }
        
        .property-card {
            margin: 0 -20px;
            border-radius: 0;
        }
    }
</style>

@endsection
