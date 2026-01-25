<section class="photo-gallery-section py-4 bg-white">
    <div class="container">
        
        <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
            @php
            $photoSlug = (isset($photoGalleryNews) && count($photoGalleryNews) > 0) ? ($photoGalleryNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $photoSlug != '#' ? route('front.category.news', $photoSlug) : '#' }}" class="text-white text-decoration-none">
            <h5 class="bg-success text-white d-inline-block px-3 py-1 m-0 fw-bold text-uppercase">ফটো গ্যালারি</h5>
        </a>
        </div>

        @if(isset($photoGalleryNews) && count($photoGalleryNews) > 0)
            <div class="row g-4">
                
                {{-- ১. স্লাইডার এরিয়া (বামের বড় অংশ - প্রথম ৩টি নিউজ) --}}
                <div class="col-lg-8">
                    <div id="photoGallerySlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        
                        {{-- ইন্ডিকেটর (নিচের ছোট বাটন) --}}
                        <div class="carousel-indicators custom-indicators">
                            @foreach($photoGalleryNews->take(3) as $key => $news)
                                <button type="button" 
                                        data-bs-target="#photoGallerySlider" 
                                        data-bs-slide-to="{{ $key }}" 
                                        class="{{ $key == 0 ? 'active' : '' }}"
                                        aria-label="Slide {{ $key + 1 }}">
                                </button>
                            @endforeach
                        </div>

                        {{-- স্লাইডার আইটেম --}}
                        <div class="carousel-inner">
                            @foreach($photoGalleryNews->take(3) as $key => $news)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="position-relative">
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url . $news->image : 'https://placehold.co/800x450/333/fff?text=Photo+Gallery' }}" 
                                             class="d-block w-100" 
                                             alt="{{ $news->title }}"
                                             style="height: 450px; object-fit: cover;">
                                        
                                        <span class="camera-icon-box"><i class="fas fa-camera"></i></span>
                                        
                                        <div class="carousel-caption d-block text-start p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); bottom: 0; left: 0; right: 0;">
                                            <h3 class="fw-bold m-0 lh-sm">
                                                <a href="{{ route('front.news.details', $news->slug) }}" class="text-white text-decoration-none">
                                                    {{ $news->title }}
                                                </a>
                                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ২. পাশের গ্রিড (ডানের ছোট অংশ - পরবর্তী ৪টি নিউজ) --}}
                <div class="col-lg-4">
                    <div class="row g-3">
                        @foreach($photoGalleryNews->slice(3, 4) as $news)
                            <div class="col-6">
                                <div class="card border-0 h-100">
                                    <div class="position-relative">
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url . $news->image : 'https://placehold.co/200x120/555/fff?text=Gallery' }}" 
                                             class="card-img-top rounded-0" 
                                             alt="{{ $news->title }}"
                                             style="height: 120px; object-fit: cover;">
                                        <span class="camera-icon-box small-icon"><i class="fas fa-camera"></i></span>
                                    </div>
                                    <div class="card-body px-0 py-2">
                                        <h6 class="fw-bold m-0 hover-red small lh-sm">
                                            <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($news->title, 50) }}
                                            </a>
                                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @else
            <div class="text-center text-muted py-5">
                <p>কোনো ফটো গ্যালারি সংবাদ পাওয়া যায়নি।</p>
            </div>
        @endif

    </div>
</section>