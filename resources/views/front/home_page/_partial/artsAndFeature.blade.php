<section class="category-grid py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            
            {{-- ১. শিল্প ও সাহিত্য (Arts & Literature) --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $artSlug = (isset($artsLiteratureNews) && count($artsLiteratureNews) > 0) ? ($artsLiteratureNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $artSlug != '#' ? route('front.category.news', $artSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">শিল্প ও সাহিত্য</h5>
        </a>
                </div>

                @if(isset($artsLiteratureNews) && count($artsLiteratureNews) > 0)
                    @php $mainArts = $artsLiteratureNews->first(); @endphp
                    
                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainArts->image ? $front_admin_url.$mainArts->image : 'https://placehold.co/400x250/555/fff?text=Arts' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainArts->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainArts->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainArts->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainArts->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainArts->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($artsLiteratureNews->skip(1) as $news)
                            <div class="card border-0 shadow-sm p-2">
                                <div class="d-flex align-items-center">
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x70/333/fff?text=News' }}" 
                                         class="me-3 rounded-1 flex-shrink-0" 
                                         width="100" height="70" style="object-fit: cover;">
                                    <h6 class="fw-bold m-0 small lh-base">
                                        <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                            {{ $news->title }}
                                        </a>
                                        <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                    </h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small">কোনো খবর পাওয়া যায়নি।</div>
                @endif
            </div>

            {{-- ২. ফিচার (Features) --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $featSlug = (isset($featureNews) && count($featureNews) > 0) ? ($featureNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $featSlug != '#' ? route('front.category.news', $featSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">ফিচার</h5>
        </a>
                </div>

                @if(isset($featureNews) && count($featureNews) > 0)
                    @php $mainFeature = $featureNews->first(); @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainFeature->image ? $front_admin_url.$mainFeature->image : 'https://placehold.co/400x250/222/fff?text=Feature' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainFeature->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainFeature->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainFeature->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainFeature->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainFeature->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($featureNews->skip(1) as $news)
                            <div class="card border-0 shadow-sm p-2">
                                <div class="d-flex align-items-center">
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x70/777/fff?text=News' }}" 
                                         class="me-3 rounded-1 flex-shrink-0" 
                                         width="100" height="70" style="object-fit: cover;">
                                    <h6 class="fw-bold m-0 small lh-base">
                                        <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                            {{ $news->title }}
                                        </a>
                                        <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                    </h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small">কোনো খবর পাওয়া যায়নি।</div>
                @endif
            </div>

            {{-- ৩. নারী (Women) --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $womSlug = (isset($womenNews) && count($womenNews) > 0) ? ($womenNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $womSlug != '#' ? route('front.category.news', $womSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">নারী</h5>
        </a>
                </div>

                @if(isset($womenNews) && count($womenNews) > 0)
                    @php $mainWomen = $womenNews->first(); @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainWomen->image ? $front_admin_url.$mainWomen->image : 'https://placehold.co/400x250/000/fff?text=Women' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainWomen->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainWomen->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainWomen->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainWomen->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainWomen->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($womenNews->skip(1) as $news)
                            <div class="card border-0 shadow-sm p-2">
                                <div class="d-flex align-items-center">
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x70/321/fff?text=News' }}" 
                                         class="me-3 rounded-1 flex-shrink-0" 
                                         width="100" height="70" style="object-fit: cover;">
                                    <h6 class="fw-bold m-0 small lh-base">
                                        <a href="{{ route('front.news.details', $news->slug) }}" class="text-dark text-decoration-none hover-red">
                                            {{ $news->title }}
                                        </a>
                                        <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                    </h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted small">কোনো খবর পাওয়া যায়নি।</div>
                @endif
            </div>

        </div>
    </div>
</section>