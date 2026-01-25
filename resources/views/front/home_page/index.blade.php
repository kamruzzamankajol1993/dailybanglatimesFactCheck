@extends('front.master.master')

@section('title')
{{ $front_ins_name }} 
@endsection

@section('css')
<style>
    /* লোডার ডিজাইন */
    #loader-area {
        padding: 40px 0;
        text-align: center;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #dc3545; /* Red Color */
    }
    
    /* ট্রানজিশন এফেক্ট */
    .fade-in-section {
        animation: fadeIn 0.8s;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Date Style - Small Font */
    .bangla-date {
        font-size: 11px;
        color: green;
        display: block;
        margin-top: 3px;
    }
</style>
@endsection

@section('body')
  
   {{-- ========================================================= --}}
   {{-- ১. সার্ভার সাইড লোডিং (Top Section - SEO Friendly)        --}}
   {{-- ========================================================= --}}
   <section class="main-content py-4">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                
                {{-- কলাম ১: ম্যাডাম নিউজ (বাম পাশ) --}}
                <div class="col-lg-3 col-md-6 order-2 order-lg-1 d-none d-lg-flex flex-column">
                    <div class="card border-0 mb-3 news-card shadow-sm h-auto flex-shrink-0">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{$front_admin_url}}{{$front_madam_image}}" class="card-img-top rounded-0" alt="News">
                        </div>
                    </div>
                    
                   <div class="left-news-list bg-white shadow-sm p-2 flex-grow-1 d-flex flex-column justify-content-between">
                        @if(isset($madamUnderNews) && count($madamUnderNews) > 0)
                            @foreach($madamUnderNews as $news)
                                <div class="d-flex {{ $loop->last ? 'mb-0 pb-0' : 'mb-2 border-bottom pb-2' }} align-items-start">
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/90x60/333/fff?text=News' }}" 
                                         class="me-2 rounded-1 flex-shrink-0" width="90" height="60" style="object-fit: cover;">
                                    <div class="w-100">
                                        @if($news->subtitle) <div class="news-subtitle">{{ $news->subtitle }}</div> @endif
                                        <a href="{{ route('front.news.details', $news->slug) }}" class="small fw-bold text-dark hover-red lh-sm text-decoration-none">
                                            {{ $news->title }}
                                        </a>
                                        <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                             <p class="text-center text-muted small mt-3">কোনো খবর পাওয়া যায়নি।</p>
                        @endif
                    </div>
                </div>

                {{-- কলাম ২: স্লাইডার এবং র‍্যান্ডম নিউজ (মাঝখান) --}}
                <div class="col-lg-5 col-md-12 order-1 order-lg-2">
                    @if(isset($sliderPosts) && count($sliderPosts) > 0)
                        <div id="mainSlider" class="carousel slide mb-3 shadow-sm" data-bs-ride="carousel" data-bs-interval="3000">
                            <div class="carousel-inner">
                                @foreach($sliderPosts as $key => $post)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $post->image ? $front_admin_url . $post->image : 'https://placehold.co/600x380/006a4e/white?text=Slider' }}" 
                                             class="d-block w-100 object-fit-cover" alt="{{ $post->title }}">
                                        <div class="carousel-caption d-none d-md-block news-caption-overlay">
                                            <h4 class="fw-bold mb-1">
                                                <a href="{{ route('front.news.details', $post->slug) }}" class="text-white text-decoration-none">{{ $post->title }}</a>
                                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($post->created_at) }}</small>
                                            </h4>
                                            @if($post->subtitle)
                                                <p class="small mb-2">{{ Str::limit($post->subtitle, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        @if(isset($randomNews) && count($randomNews) > 0)
                            @foreach($randomNews as $news)
                                <div class="col-6">
                                    <div class="card border-0 h-100 shadow-sm">
                                        <div class="overflow-hidden">
                                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url . $news->image : 'https://placehold.co/300x180/333/fff?text=News' }}" 
                                                 class="card-img-top rounded-0 zoom-effect" alt="{{ $news->title }}">
                                        </div>
                                        <div class="card-body p-2">
                                            @if($news->subtitle) <div class="news-subtitle">{{ $news->subtitle }}</div> @endif
                                            <h6 class="fw-bold hover-red title-truncate">
                                                <a href="{{ route('front.news.details', $news->slug) }}" class=" hover-red text-dark text-decoration-none">{{ $news->title }}</a>

                                            </h6>
                                                                                        <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- কলাম ৩: লেটেস্ট ও পপুলার ট্যাব (ডান পাশ) --}}
                <div class="col-lg-4 col-md-6 order-3 order-lg-3 d-flex flex-column">
                    <div class="bg-white border p-0 mb-4 shadow-sm flex-grow-1 d-flex flex-column">
                        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-0 fw-bold py-2 custom-tab-btn tab-green" id="latest-tab" data-bs-toggle="pill" data-bs-target="#latest">সর্বশেষ</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-0 fw-bold py-2 custom-tab-btn tab-red" id="popular-tab" data-bs-toggle="pill" data-bs-target="#popular">জনপ্রিয়</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-2 custom-scrollbar flex-grow-1" style="max-height: 800px; overflow-y: auto;">
                            {{-- Latest Tab --}}
                            <div class="tab-pane fade show active" id="latest">
                                @if(isset($latestPosts) && count($latestPosts) > 0)
                                    @foreach($latestPosts as $post)
                                        <div class="news-item border-bottom py-2 d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                 <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $post->image ? $front_admin_url . $post->image : 'https://placehold.co/90x60/111/fff?text=No+Image' }}" 
                                                      class="me-2 rounded-1" width="90" height="60" style="object-fit: cover;">
                                            </div>
                                            <div class="w-100">
                                                @if($post->subtitle) <div class="news-subtitle">{{ $post->subtitle }}</div> @endif
                                                <a href="{{ route('front.news.details', $post->slug) }}" class="small fw-bold text-dark hover-red lh-sm text-decoration-none">{{ $post->title }}</a>
                                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($post->created_at) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center text-muted small mt-3">কোনো খবর পাওয়া যায়নি।</p>
                                @endif
                            </div>
                            
                            {{-- Popular Tab --}}
                            <div class="tab-pane fade" id="popular">
                                @if(isset($popularPosts) && count($popularPosts) > 0)
                                    @foreach($popularPosts as $post)
                                        <div class="news-item border-bottom py-2 d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                 <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $post->image ? $front_admin_url . $post->image : 'https://placehold.co/90x60/222/fff?text=No+Image' }}" 
                                                      class="me-2 rounded-1" width="90" height="60" style="object-fit: cover;">
                                            </div>
                                            <div class="w-100">
                                                @if($post->subtitle) <div class="news-subtitle">{{ $post->subtitle }}</div> @endif
                                                <a href="{{ route('front.news.details', $post->slug) }}" class="small fw-bold text-dark hover-red lh-sm text-decoration-none">{{ $post->title }}</a>
                                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($post->created_at) }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center text-muted small mt-3">কোনো খবর পাওয়া যায়নি।</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- সাইডবার অ্যাড --}}
                    <div class="ad-container text-center mt-auto">
                        <div class="animated-ad-box">
                            <a href="https://zahidfsardersaddi.com/" target="_blank" rel="noopener">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{$front_admin_url}}{{$front_personal_logo}}" alt="Ad" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

   

    {{-- ========================================================= --}}
    {{-- ২. ক্লায়েন্ট সাইড ব্যাচ লোডিং (AJAX - Bottom Sections)    --}}
    {{-- ========================================================= --}}
    
    <div id="dynamic-content-area">
        {{-- এখানে জাভাস্ক্রিপ্ট এর মাধ্যমে ডাটা ধাপে ধাপে আসবে --}}
    </div>

    {{-- লোডার --}}
    <div id="loader-area" class="d-none">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 fw-bold text-muted">আরও সংবাদ লোড হচ্ছে...</p>
    </div>
 @include('front.home_page._partial.namazTimes')
 
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let currentStep = 1;
        let isLoading = false;
        let hasMore = true;

        function loadMoreSections() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            $('#loader-area').removeClass('d-none');

            $.ajax({
                url: "{{ route('front.load.more.news') }}", 
                type: "GET",
                data: { step: currentStep },
                success: function(response) {
                    // ১. নতুন HTML যোগ করা
                    let newContent = $(response.html).addClass('fade-in-section');
                    $('#dynamic-content-area').append(newContent);

                    // ২. স্লাইডার রি-ইনিশিয়ালাইজ করা (Fix for Auto Slider)
                    
                    // ফটো গ্যালারি (যদি থাকে)
                    var photoSlider = document.querySelector('#photoGallerySlider');
                    if (photoSlider) {
                        new bootstrap.Carousel(photoSlider, {
                            interval: 3000, 
                            ride: 'carousel'
                        });
                    }

                    // ============================================================
                    // ফিক্স: ভিডিও গ্যালারির নতুন ৩টি স্লাইডার ইনিশিয়ালাইজ করা
                    // ============================================================
                    
                    // ডেস্কটপ স্লাইডার
                    var videoDesk = document.querySelector('#videoCarouselDesktop');
                    if (videoDesk) {
                        new bootstrap.Carousel(videoDesk, { interval: 4000, ride: 'carousel' });
                    }

                    // ট্যাবলেট স্লাইডার
                    var videoTab = document.querySelector('#videoCarouselTablet');
                    if (videoTab) {
                        new bootstrap.Carousel(videoTab, { interval: 4000, ride: 'carousel' });
                    }

                    // মোবাইল স্লাইডার
                    var videoMob = document.querySelector('#videoCarouselMobile');
                    if (videoMob) {
                        new bootstrap.Carousel(videoMob, { interval: 4000, ride: 'carousel' });
                    }

                    // ৩. ভেরিয়েবল আপডেট
                    hasMore = response.hasMore;
                    currentStep = response.nextStep;
                    isLoading = false;
                    
                    // ৪. অটোমেটিক পরের সেকশন লোড (Recursive Call)
                    if (hasMore) {
                        setTimeout(function() {
                            loadMoreSections();
                        }, 300);
                    } else {
                        $('#loader-area').addClass('d-none');
                    }
                },
                error: function() {
                    isLoading = false;
                    $('#loader-area').addClass('d-none');
                    console.log('Failed to load news sections.');
                }
            });
        }

        // লোড শুরু করার কমান্ড
        setTimeout(loadMoreSections, 1000);
    });
</script>
@endsection