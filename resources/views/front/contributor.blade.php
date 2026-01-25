@extends('front.master.master')

@section('title')
    কন্ট্রিবিউটর | ডেইলি বাংলা টাইমস
@endsection

@section('css')
 <style>
        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: #dc3545;
        }
        
        /* Card Styles */
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
        
        .member-img-box {
            position: relative;
            overflow: hidden;
            height: 280px; 
            width: 100%;
            background-color: #f8f9fa;
        }
        .member-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            object-position: top center;
            transition: transform 0.5s ease;
        }
        .member-card:hover .member-img-box img {
            transform: scale(1.1);
        }

        /* Social Icons */
        .member-social {
            position: absolute;
            bottom: -60px;
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

        /* Detail Button */
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

        /* Modal Image */
        .team-modal-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            object-position: top center;
            border: 3px solid #dee2e6;
            padding: 3px;
        }
    </style>
@endsection

@section('body')

{{-- Icon Helper --}}
@php
    function getSocialIconClass($name, $dbIcon) {
        if(!empty($dbIcon)) return $dbIcon; 
        $name = strtolower($name);
        if(str_contains($name, 'facebook')) return 'fab fa-facebook-f';
        if(str_contains($name, 'twitter') || str_contains($name, 'x')) return 'fab fa-twitter';
        if(str_contains($name, 'linkedin')) return 'fab fa-linkedin-in';
        if(str_contains($name, 'instagram')) return 'fab fa-instagram';
        if(str_contains($name, 'youtube')) return 'fab fa-youtube';
        if(str_contains($name, 'email') || str_contains($name, 'mail')) return 'fas fa-envelope';
        return 'fas fa-link'; 
    }
@endphp

  <section class="py-5 bg-light">
        <div class="container">
            
            <div class="text-center mb-5">
                <h1 class="fw-bold">{{ $category->name ?? 'কন্ট্রিবিউটর' }}</h1>
                <p class="text-secondary">আমাদের সম্মানিত কন্ট্রিবিউটরবৃন্দ</p>
                <div class="section-title"></div>
            </div>

            @if($contributors->count() > 0)
                <div class="row g-4">
                    @foreach($contributors as $member)
                    <div class="col-lg-3 col-md-6">
                        {{-- Card --}}
                        <div class="card border-0 shadow-sm member-card h-100">
                            <div class="member-img-box">
                                <img src="{{ $front_admin_url.'public/'.$member->image }}" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'" alt="{{ $member->name }}">
                                
                                {{-- Social --}}
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
                                
                                {{-- Show Designations --}}
                                @php
                                    $desigNames = \App\Models\Designation::whereIn('id', $member->designation_id ?? [])->pluck('name')->join(', ');
                                @endphp
                                <p class="text-success fw-bold small mb-3">{{ $desigNames }}</p>

                                <button type="button" class="btn btn-detail" data-bs-toggle="modal" data-bs-target="#conModal-{{ $member->id }}">
                                    বিস্তারিত
                                </button>
                            </div>
                        </div>

                        {{-- Modal --}}
                        <div class="modal fade" id="conModal-{{ $member->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body text-center pt-0 pb-4 px-4">
                                <img src="{{ $front_admin_url.'public/'.$member->image }}" class="rounded-circle team-modal-img mb-3" onerror="this.src='{{ $front_admin_url }}{{ $front_logo_name }}'">
                                <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                                <p class="text-danger small fw-bold mb-3">{{ $desigNames }}</p>
                                
                                <div class="text-start bg-light p-3 rounded small text-secondary">
                                     {!! $member->bio ?? $member->short_description ?? 'বিস্তারিত তথ্য শীঘ্রই যুক্ত করা হবে।' !!}
                                </div>

                                @if($member->socialLinks->count() > 0)
                                <div class="mt-3">
                                     @foreach($member->socialLinks as $social)
                                        @php $icon = getSocialIconClass($social->name, $social->icon); @endphp
                                        <a href="{{ $social->url }}" target="_blank" class="text-dark mx-2 fs-5"><i class="{{ $icon }}"></i></a>
                                    @endforeach
                                </div>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- End Modal --}}
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <h4 class="text-muted">বর্তমানে কোন তথ্য পাওয়া যায়নি।</h4>
                </div>
            @endif

        </div>
    </section>
@endsection