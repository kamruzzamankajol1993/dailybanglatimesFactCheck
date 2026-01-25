<section class="intl-section py-4 bg-white">
    <div class="container">
        <div class="row g-4">
            
            {{-- International News Part --}}
            <div class="col-lg-9">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $intlSlug = (isset($internationalNews) && count($internationalNews) > 0) ? ($internationalNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $intlSlug != '#' ? route('front.category.news', $intlSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative" style="top: 0;">আন্তর্জাতিক</h5>
        </a>
                </div>

                <div class="row g-4 d-flex align-items-stretch">
                    
                    @if(isset($internationalNews) && count($internationalNews) > 0)
                        
                        {{-- 1. Main Big News (First Item) --}}
                        @php $mainNews = $internationalNews->first(); @endphp
                        <div class="col-lg-7 d-flex flex-column">
                            <div class="card border-0 h-100">
                                <div class="position-relative">
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainNews->image ? $front_admin_url.$mainNews->image : 'https://placehold.co/600x350/eee/333?text=International' }}" 
                                         class="card-img-top rounded-0 mb-3 w-100" 
                                         alt="{{ $mainNews->title }}">
                                    <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                                </div>
                                <h3 class="fw-bold mb-3 hover-red" style="line-height: 1.4;">
                                    <a href="{{ route('front.news.details', $mainNews->slug) }}" class="text-dark text-decoration-none hover-red">
                                        {{ $mainNews->title }}
                                    </a>
                                     <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainNews->created_at) }}</small>
                                </h3>
                                
                                <p class="text-secondary text-justify flex-grow-1">
                                    {{ Str::limit(strip_tags($mainNews->content), 250) }}
                                    <a href="{{ route('front.news.details', $mainNews->slug) }}" class="text-danger fw-bold text-decoration-none">বিস্তারিত</a>
                                </p>
                            </div>
                        </div>

                        {{-- 2. Side List News (Remaining 5 Items) --}}
                        <div class="col-lg-5 d-flex flex-column">
                            <div class="d-flex flex-column justify-content-between h-100">
                                @foreach($internationalNews->skip(1) as $news)
                                    <div class="card border-0 shadow-sm p-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/120x80/eee/333?text=News' }}" 
                                                 class="me-3 rounded-1 flex-shrink-0" 
                                                 width="100" height="70"
                                                 style="object-fit: cover;">
                                            <h6 class="fw-bold m-0 lh-sm small">
                                                <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                                    {{ $news->title }}
                                                </a>
                                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @else
                        <div class="col-12 text-center text-muted">
                            <p>কোনো আন্তর্জাতিক খবর পাওয়া যায়নি।</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Sidebar (Opinion & Social) --}}
            <div class="col-lg-3 col-md-6 mx-auto">
                
                {{-- Facebook Page Plugin --}}
                <div class="mb-4">
                    <h6 class="text-danger fw-bold border-bottom pb-2 mb-3">FOLLOW US</h6>
                    <div class="facebook-wrapper border p-1 bg-light">
                         <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FDailyBanglaTimesUSA.BD&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="100%" height="250" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>

                {{-- Opinion Section --}}
                <div>
                    <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                        @php
            $opSlug = (isset($opinionNews) && count($opinionNews) > 0) ? ($opinionNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $opSlug != '#' ? route('front.category.news', $opSlug) : '#' }}" class="text-white text-decoration-none">
                        <h6 class="bg-success text-white d-inline-block px-3 py-1 m-0">সাক্ষাৎকার</h6>
        </a>
        
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @if(isset($opinionNews) && count($opinionNews) > 0)
                            @foreach($opinionNews as $news)
                                <div class="card border-0 bg-light">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-4">
                                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x100/333/fff?text=Opinion' }}" 
                                                 class="img-fluid rounded-start h-100 object-fit-cover"
                                                 style="height: 80px;">
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-2">
                                                {{-- এখানে subtitle কে লেখকের নাম হিসেবে ব্যবহার করা হয়েছে --}}
                                                @if($news->subtitle)
                                                    <small class="text-danger fw-bold d-block mb-1">{{ $news->subtitle }}</small>
                                                @endif
                                                <h6 class="card-title fs-6 m-0 lh-sm">
                                                    <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                                        {{ $news->title }}
                                                    </a>
                                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small">কোনো মতামত পাওয়া যায়নি।</p>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>