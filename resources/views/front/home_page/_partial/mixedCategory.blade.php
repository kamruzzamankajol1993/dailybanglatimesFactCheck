<section class="mixed-category-section py-4 bg-light">
    <div class="container">
        <div class="row g-3">
            
            {{-- লুপ চালানোর সুবিধার্থে একটি অ্যারে তৈরি করা হলো --}}
            @php
                $mixedSections = [
                    ['title' => 'সোশ্যাল মিডিয়া',    'data' => $socialMediaNews ?? collect()],
                    ['title' => 'ব্যবসা বানিজ্য',     'data' => $businessNews ?? collect()],
                    ['title' => 'ধর্ম ও জীবন',        'data' => $religionLifeNews ?? collect()],
                    ['title' => 'বিজ্ঞান ও প্রযুক্তি', 'data' => $sciTechNews ?? collect()],
                ];
            @endphp

            @foreach($mixedSections as $section)
                <div class="col-lg-3 col-md-6">
                    {{-- Header --}}
                    <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">

                        @php
            // ডাটা থাকলে প্রথম পোস্ট থেকে ক্যাটাগরি স্লাগ বের করা হচ্ছে
            $mixSlug = ($section['data']->count() > 0) ? ($section['data']->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        
        <a href="{{ $mixSlug != '#' ? route('front.category.news', $mixSlug) : '#' }}" class="text-white text-decoration-none">
                        <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative" style="font-size: 16px;">
                            {{ $section['title'] }}
                        </h5>
        </a>
                    </div>

                    @if($section['data']->count() > 0)
                        @php $mainItem = $section['data']->first(); @endphp

                        {{-- Main Item (Big Image) --}}
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainItem->image ? $front_admin_url.$mainItem->image : 'https://placehold.co/400x250/333/fff?text='.$section['title'] }}" 
                                     class="card-img-top rounded-0 extra-cat-main-img" 
                                     alt="{{ $mainItem->title }}">
                                <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title fw-bold hover-red lh-base mb-2">
                                    <a href="{{ route('front.news.details', $mainItem->slug) }}" class="text-dark text-decoration-none">
                                        {{ $mainItem->title }}
                                    </a>
                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainItem->created_at) }}</small>
                                </h6>
                                
                            </div>
                        </div>

                        {{-- List Items (Small Image) --}}
                        <div class="d-flex flex-column gap-2">
                            @foreach($section['data']->skip(1) as $news)
                                <div class="card border-0 shadow-sm p-2">
                                    <div class="d-flex align-items-start">
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x70/555/fff?text=News' }}" 
                                             class="me-2 rounded-1 flex-shrink-0 extra-cat-list-img" 
                                             alt="Thumb">
                                        <h6 class="fw-bold m-0 small lh-sm hover-red">
                                            <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($news->title, 50) }}
                                            </a>
                                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted small">খবর আপডেট হচ্ছে...</div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</section>