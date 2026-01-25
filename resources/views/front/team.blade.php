@extends('front.master.master')

@section('title')
    টিম | ডেইলি বাংলা টাইমস
@endsection

@section('css')
<style>
        /* --- Section Title --- */
        .team-section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .team-section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #dc3545;
        }
        
        /* --- Member Card --- */
        .member-card {
            transition: all 0.3s ease;
            overflow: hidden;
            border-bottom: 3px solid transparent;
            background: #fff;
        }
        .member-card:hover {
            transform: translateY(-5px);
            border-bottom-color: #dc3545;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        /* --- Image Box (FIXED SIZE + TOP ALIGNMENT) --- */
        .member-img-box {
            position: relative;
            overflow: hidden;
            height: 280px; /* <--- FIXED HEIGHT RESTORED */
            background-color: #f8f9fa;
            width: 100%;
        }
        .member-img-box img {
            width: 100%;
            height: 100%; /* <--- Force fill the container */
            object-fit: cover; /* <--- Ensures image covers area without stretching */
            object-position: top center; /* <--- IMPORTANT: Crops from bottom, keeps head visible */
            transition: transform 0.5s ease;
        }
        .member-card:hover .member-img-box img {
            transform: scale(1.1);
        }

        /* --- Floating Social Icons --- */
        .member-social {
            position: absolute;
            bottom: -60px; /* Hidden initially */
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 0;
            text-align: center;
            transition: bottom 0.3s ease;
            border-top: 2px solid #dc3545;
            z-index: 2;
        }
        .member-card:hover .member-social {
            bottom: 0;
        }
        .member-social a {
            color: #333;
            margin: 0 8px;
            font-size: 16px;
            transition: 0.2s;
        }
        .member-social a:hover { color: #dc3545; }

        /* --- Detail Button --- */
        .btn-detail {
            font-size: 13px;
            padding: 5px 20px;
            border-radius: 50px;
            border: 1px solid #dee2e6;
            color: #666;
            background: #fff;
            transition: all 0.3s;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
        .btn-detail:hover {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        /* --- Modal Styles --- */
        .team-modal-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            object-position: top center; /* Ensure modal image also focuses on face */
            border: 3px solid #dee2e6;
            padding: 3px;
        }
    </style>
@endsection

@section('body')

{{-- Helper Function to detect Icon based on Name if DB icon is missing --}}
@php
    function getSocialIconClass($name, $dbIcon) {
        if(!empty($dbIcon)) return $dbIcon; // Use DB icon if user uploaded/selected one
        
        $name = strtolower($name);
        if(str_contains($name, 'facebook')) return 'fab fa-facebook-f';
        if(str_contains($name, 'twitter') || str_contains($name, 'x')) return 'fab fa-twitter';
        if(str_contains($name, 'linkedin')) return 'fab fa-linkedin-in';
        if(str_contains($name, 'instagram')) return 'fab fa-instagram';
        if(str_contains($name, 'youtube')) return 'fab fa-youtube';
        if(str_contains($name, 'email') || str_contains($name, 'mail')) return 'fas fa-envelope';
        if(str_contains($name, 'web') || str_contains($name, 'site')) return 'fas fa-globe';
        
        return 'fas fa-link'; // Default generic icon
    }
@endphp

  <section class="py-5 bg-light">
        <div class="container">
            
            <div class="text-center mb-5">
                <h1 class="fw-bold">ডেইলি বাংলা টাইমস পরিবার</h1>
                <p class="text-secondary">বস্তুনিষ্ঠ সংবাদ পরিবেশনে যারা নিরলস কাজ করে যাচ্ছেন</p>
            </div>

            {{-- ==========================================
                 1. TOP LEADERS SECTION (Publisher / Editor)
                 ========================================== --}}
            @if(isset($topLeaders) && $topLeaders->count() > 0)
            <div class="mb-5">
                <div class="text-center">
                    <h3 class="team-section-title fw-bold text-dark">সম্পাদনা পরিষদ</h3>
                </div>
                
                <div class="row g-4 justify-content-center">
                    @foreach($topLeaders as $member)
                        <div class="col-lg-4 col-md-6">
                            {{-- Card Start --}}
                            <div class="card border-0 shadow-sm member-card h-100">
                                <div class="member-img-box">
                                    <img src="{{ $front_admin_url.'public/'.$member->image }}" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'" alt="{{ $member->name }}">
                                    
                                    {{-- Social Icons --}}
                                    @if($member->socialLinks->count() > 0)
                                    <div class="member-social">
                                        @foreach($member->socialLinks as $social)
                                            @php $icon = getSocialIconClass($social->name, $social->icon); @endphp
                                            <a href="{{ $social->url }}" target="_blank" title="{{ $social->name }}">
                                                <i class="{{ $icon }}"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body text-center p-4">
                                    <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                                    <p class="text-danger fw-bold small text-uppercase mb-3">
                                        {{ $member->designations->pluck('name')->join(', ') }}
                                    </p>
                                    
                                    {{-- Detail Button (Trigger Modal) --}}
                                    <button type="button" class="btn btn-detail" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $member->id }}">
                                        বিস্তারিত দেখুন
                                    </button>
                                </div>
                            </div>
                            {{-- Card End --}}

                            {{-- Modal Include (Defined at bottom of loop for clean structure) --}}
                          <div class="modal fade" id="teamModal-{{ $member->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $member->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 text-center border-end">
                <img src="{{ $front_admin_url.'public/'.$member->image }}" class="rounded-circle team-modal-img mb-3" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'">
                <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                <p class="text-danger small fw-bold text-uppercase mb-3">{{ $member->designations->pluck('name')->join(', ') }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                     @foreach($member->socialLinks as $social)
                        @php $icon = getSocialIconClass($social->name, $social->icon); @endphp
                        <a href="{{ $social->url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle" style="width:35px; height:35px; display:flex; align-items:center; justify-content:center;">
                            <i class="{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <h6 class="fw-bold border-bottom pb-2 mb-3">পরিচিতি ও বিস্তারিত</h6>
                <div class="text-secondary small lh-lg">
                    {!! $member->bio ?? $member->short_description ?? 'বিস্তারিত তথ্য শীঘ্রই যুক্ত করা হবে।' !!}
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif


            {{-- ==========================================
                 2. CATEGORY WISE MEMBERS SECTION
                 ========================================== --}}
            @if(isset($categorizedTeam))
                @foreach($categorizedTeam as $group)
                <div class="mb-5">
                    <div class="text-center">
                        <h3 class="team-section-title fw-bold text-dark">{{ $group['info']->name }}</h3>
                    </div>

                    <div class="row g-4">
                        @foreach($group['members'] as $member)
                        <div class="col-lg-3 col-md-6">
                            {{-- Card Start --}}
                            <div class="card border-0 shadow-sm member-card h-100">
                                <div class="member-img-box">
                                    <img src="{{ $front_admin_url.'public/'.$member->image }}" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'" alt="{{ $member->name }}">
                                    
                                    {{-- Social Icons --}}
                                    @if($member->socialLinks->count() > 0)
                                    <div class="member-social">
                                        @foreach($member->socialLinks as $social)
                                            @php $icon = getSocialIconClass($social->name, $social->icon); @endphp
                                            <a href="{{ $social->url }}" target="_blank" title="{{ $social->name }}">
                                                <i class="{{ $icon }}"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                <div class="card-body text-center">
                                    <h6 class="fw-bold mb-1">{{ $member->name }}</h6>
                                    <p class="text-success fw-bold small mb-3">
                                        {{ $member->designations->pluck('name')->join(', ') }}
                                    </p>

                                    {{-- Detail Button (Trigger Modal) --}}
                                    <button type="button" class="btn btn-detail" data-bs-toggle="modal" data-bs-target="#teamModal-{{ $member->id }}">
                                        বিস্তারিত
                                    </button>
                                </div>
                            </div>
                            {{-- Card End --}}

                            {{-- Modal Include --}}
                           <div class="modal fade" id="teamModal-{{ $member->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $member->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 text-center border-end">
                <img src="{{ $front_admin_url.'public/'.$member->image }}" class="rounded-circle team-modal-img mb-3" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'">
                <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                <p class="text-danger small fw-bold text-uppercase mb-3">{{ $member->designations->pluck('name')->join(', ') }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                     @foreach($member->socialLinks as $social)
                        @php $icon = getSocialIconClass($social->name, $social->icon); @endphp
                        <a href="{{ $social->url }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle" style="width:35px; height:35px; display:flex; align-items:center; justify-content:center;">
                            <i class="{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-8">
                <h6 class="fw-bold border-bottom pb-2 mb-3">পরিচিতি ও বিস্তারিত</h6>
                <div class="text-secondary small lh-lg">
                    {!! $member->bio ?? $member->short_description ?? 'বিস্তারিত তথ্য শীঘ্রই যুক্ত করা হবে।' !!}
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </section>

@endsection

@section('scripts')
    {{-- Ensure Bootstrap JS is loaded in your master layout for Modals to work --}}
@endsection