<section class="category-grid py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            
            {{-- ১. আইন-আদালত (Law & Court) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $lawSlug = (isset($lawCourtsNews) && count($lawCourtsNews) > 0) ? ($lawCourtsNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $lawSlug != '#' ? route('front.category.news', $lawSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">আইন-আদালত</h5>
        </a>
                </div>

                @if(isset($lawCourtsNews) && count($lawCourtsNews) > 0)
                    @php $mainLaw = $lawCourtsNews->first(); @endphp
                    
                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainLaw->image ? $front_admin_url.$mainLaw->image : 'https://placehold.co/400x250/555/fff?text=Law' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainLaw->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainLaw->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainLaw->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainLaw->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainLaw->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($lawCourtsNews->skip(1) as $news)
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

            {{-- ২. এক্সক্লুসিভ (Exclusive) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $exclSlug = (isset($exclusiveNews) && count($exclusiveNews) > 0) ? ($exclusiveNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $exclSlug != '#' ? route('front.category.news', $exclSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">এক্সক্লুসিভ</h5>
        </a>
                </div>

                @if(isset($exclusiveNews) && count($exclusiveNews) > 0)
                    @php $mainExclusive = $exclusiveNews->first(); @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainExclusive->image ? $front_admin_url.$mainExclusive->image : 'https://placehold.co/400x250/222/fff?text=Exclusive' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainExclusive->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainExclusive->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainExclusive->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainExclusive->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainExclusive->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($exclusiveNews->skip(1) as $news)
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

            {{-- ৩. স্বাস্থ্য (Health) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $healthSlug = (isset($healthNews) && count($healthNews) > 0) ? ($healthNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $healthSlug != '#' ? route('front.category.news', $healthSlug) : '#' }}" class="text-white text-decoration-none">
        
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">স্বাস্থ্য</h5>

        </a>
                </div>

                @if(isset($healthNews) && count($healthNews) > 0)
                    @php $mainHealth = $healthNews->first(); @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainHealth->image ? $front_admin_url.$mainHealth->image : 'https://placehold.co/400x250/000/fff?text=Health' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainHealth->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainHealth->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainHealth->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainHealth->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainHealth->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($healthNews->skip(1) as $news)
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