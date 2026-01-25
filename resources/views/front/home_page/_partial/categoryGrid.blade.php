


<section class="category-grid py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            
            {{-- ১. জাতীয় (National) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $natSlug = (isset($nationalNews) && count($nationalNews) > 0) ? ($nationalNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $natSlug != '#' ? route('front.category.news', $natSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">জাতীয়</h5>
        </a>
                </div>

                @if(isset($nationalNews) && count($nationalNews) > 0)
                    @php 
                        $mainNational = $nationalNews->first(); 
                    @endphp
                    
                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainNational->image ? $front_admin_url.$mainNational->image : 'https://placehold.co/400x250/555/fff?text=National' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainNational->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainNational->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainNational->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainNational->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainNational->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($nationalNews->skip(1) as $news)
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
                @endif
            </div>

            {{-- ২. রাজনীতি (Politics) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $polSlug = (isset($politicsNews) && count($politicsNews) > 0) ? ($politicsNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $polSlug != '#' ? route('front.category.news', $polSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">রাজনীতি</h5>
        </a>
                </div>

                @if(isset($politicsNews) && count($politicsNews) > 0)
                    @php 
                        $mainPolitics = $politicsNews->first(); 
                    @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainPolitics->image ? $front_admin_url.$mainPolitics->image : 'https://placehold.co/400x250/222/fff?text=Politics' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainPolitics->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainPolitics->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainPolitics->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainPolitics->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainPolitics->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($politicsNews->skip(1) as $news)
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
                @endif
            </div>

            {{-- ৩. অর্থনীতি (Economy) Column --}}
            <div class="col-lg-4">
                <div class="section-header-wrapper mb-3" style="border-bottom: 3px solid #dc3545;">
                    @php
            $ecoSlug = (isset($economyNews) && count($economyNews) > 0) ? ($economyNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $ecoSlug != '#' ? route('front.category.news', $ecoSlug) : '#' }}" class="text-white text-decoration-none">
                    <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">অর্থনীতি</h5>
        </a>
                </div>

                @if(isset($economyNews) && count($economyNews) > 0)
                    @php 
                        $mainEconomy = $economyNews->first(); 
                    @endphp

                    {{-- Main Card --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="position-relative">
                            <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainEconomy->image ? $front_admin_url.$mainEconomy->image : 'https://placehold.co/400x250/000/fff?text=Economy' }}" 
                                 class="card-img-top rounded-0" 
                                 alt="{{ $mainEconomy->title }}">
                            <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="card-body p-3">
                            <h5 class="card-title fw-bold hover-red">
                                <a href="{{ route('front.news.details', $mainEconomy->slug) }}" class="text-dark text-decoration-none hover-red">
                                    {{ $mainEconomy->title }}
                                </a>
                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainEconomy->created_at) }}</small>
                            </h5>
                            <p class="card-text small text-secondary text-justify">
                                {{ Str::limit(strip_tags($mainEconomy->content), 150) }}
                            </p>
                        </div>
                    </div>

                    {{-- List Items --}}
                    <div class="d-flex flex-column gap-2">
                        @foreach($economyNews->skip(1) as $news)
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
                @endif
            </div>

        </div>
    </div>
</section>