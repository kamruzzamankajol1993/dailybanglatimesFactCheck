<section class="lifestyle-section py-4 bg-white">
    <div class="container">
        
        <div class="section-header-wrapper mb-4" style="border-bottom: 2px solid #dc3545;">

            @php
            $lifeSlug = (isset($lifestyleNews) && count($lifestyleNews) > 0) ? ($lifestyleNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $lifeSlug != '#' ? route('front.category.news', $lifeSlug) : '#' }}" class="text-white text-decoration-none">

            <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 fw-bold">লাইফস্টাইল</h5>

        </a>
        </div>

        @if(isset($lifestyleNews) && count($lifestyleNews) > 0)
            
            {{-- প্রথম সারি: ৩টি বড় কার্ড (Index 0-2) --}}
            <div class="row g-4 mb-4">
                @foreach($lifestyleNews->take(3) as $news)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 h-100 lifestyle-card">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/400x240/333/fff?text=Lifestyle' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $news->title }}"
                                     style="height: 240px; object-fit: cover;">
                                <span class="camera-icon-box"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body px-0 pb-2">
                              
                                <h6 class="fw-bold m-0 lh-base">
                                    <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                        {{ $news->title }}
                                    </a>
                                </h6>
                                  <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- দ্বিতীয় সারি: ৪টি ছোট কার্ড (Index 3-6) --}}
            <div class="row g-4">
                @foreach($lifestyleNews->slice(3, 4) as $news)
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 h-100 lifestyle-card">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/300x180/222/fff?text=Lifestyle' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $news->title }}"
                                     style="height: 180px; object-fit: cover;">
                                <span class="camera-icon-box"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body px-0 pb-2">
                                
                                <h6 class="fw-bold m-0 lh-base small">
                                    <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                        {{ $news->title }}
                                    </a>
                                </h6>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="text-center text-muted py-5">
                <p>কোনো লাইফস্টাইল সংবাদ পাওয়া যায়নি।</p>
            </div>
        @endif

    </div>
</section>