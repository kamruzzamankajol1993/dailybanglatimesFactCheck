<section class="sports-section py-4 bg-white">
    <div class="container">
        
        <div class="section-header-wrapper mb-4" style="border-bottom: 3px solid #dc3545;">
            @php
            $sportSlug = (isset($sportsNews) && count($sportsNews) > 0) ? ($sportsNews->first()->categories->first()->slug ?? '#') : '#';
        @endphp
        <a href="{{ $sportSlug != '#' ? route('front.category.news', $sportSlug) : '#' }}" class="text-white text-decoration-none">
            <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">খেলা</h5>
        </a>
        </div>

        <div class="row g-4 d-flex align-items-stretch">
            
            @if(isset($sportsNews) && count($sportsNews) > 0)
                
                {{-- কলাম ১: ১টি মেইন নিউজ + ২টি ছোট নিউজ (Total 3 items: Index 0, 1, 2) --}}
                <div class="col-lg-4 d-flex flex-column">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        
                        {{-- Col 1: Main Item (Index 0) --}}
                        @php $sport1 = $sportsNews->first(); @endphp
                        @if($sport1)
                        <div class="card border-0 mb-3">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $sport1->image ? $front_admin_url.$sport1->image : 'https://placehold.co/400x250/333/fff?text=Sports' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $sport1->title }}">
                                <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body px-0 py-2">
                                {{-- Subtitle --}}
                                @if($sport1->subtitle)
                                    <div class="news-subtitle">{{ $sport1->subtitle }}</div>
                                @endif
                                <h5 class="card-title fw-bold hover-red">
                                    <a href="{{ route('front.news.details', $sport1->slug) }}" class="hover-red text-dark text-decoration-none">
                                        {{ $sport1->title }}
                                    </a>
                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($sport1->created_at) }}</small>
                                </h5>
                                <p class="card-text small text-secondary">
                                    {{ Str::limit(strip_tags($sport1->content), 100) }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Col 1: Sub Items (Index 1, 2) --}}
                        {{-- আপডেট: ইমেজ ডান পাশে নেওয়া হয়েছে --}}
                        <div class="d-flex flex-column gap-3">
                            @foreach($sportsNews->slice(1, 2) as $news)
                                <div class="card border-0 shadow-sm p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        {{-- টেক্সট বামে --}}
                                        <div class="me-2">
                                            @if($news->subtitle)
                                                <div class="news-subtitle" style="font-size: 11px;">{{ $news->subtitle }}</div>
                                            @endif
                                            <h6 class="fw-bold m-0 hover-red small lh-base">
                                                <a href="{{ route('front.news.details', $news->slug) }}" class="hover-red text-dark text-decoration-none">
                                                    {{ $news->title }}
                                                </a>
                                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                            </h6>
                                        </div>
                                        {{-- ইমেজ ডানে --}}
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x60/222/fff?text=Sports' }}" 
                                             class="rounded-1 flex-shrink-0" 
                                             width="100" height="60" style="object-fit: cover;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- কলাম ২: ১টি মেইন নিউজ + ২টি ছোট নিউজ (Total 3 items: Index 3, 4, 5) --}}
                <div class="col-lg-4 d-flex flex-column">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        
                        {{-- Col 2: Main Item (Index 3) --}}
                        @php $sport2 = $sportsNews->slice(3, 1)->first(); @endphp
                        @if($sport2)
                        <div class="card border-0 mb-3">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $sport2->image ? $front_admin_url.$sport2->image : 'https://placehold.co/400x250/006a4e/fff?text=Sports' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $sport2->title }}">
                                <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body px-0 py-2">
                                {{-- Subtitle --}}
                                @if($sport2->subtitle)
                                    <div class="news-subtitle">{{ $sport2->subtitle }}</div>
                                @endif
                                <h5 class="card-title fw-bold hover-red">
                                    <a href="{{ route('front.news.details', $sport2->slug) }}" class="hover-red text-dark text-decoration-none">
                                        {{ $sport2->title }}
                                    </a>
                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($sport2->created_at) }}</small>
                                </h5>
                                <p class="card-text small text-secondary">
                                    {{ Str::limit(strip_tags($sport2->content), 100) }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Col 2: Sub Items (Index 4, 5) --}}
                        {{-- আপডেট: ইমেজ ডান পাশে নেওয়া হয়েছে --}}
                        <div class="d-flex flex-column gap-3">
                            @foreach($sportsNews->slice(4, 2) as $news)
                                <div class="card border-0 shadow-sm p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        {{-- টেক্সট বামে --}}
                                        <div class="me-2">
                                            @if($news->subtitle)
                                                <div class="news-subtitle" style="font-size: 11px;">{{ $news->subtitle }}</div>
                                            @endif
                                            <h6 class="fw-bold m-0 hover-red small lh-base">
                                                <a href="{{ route('front.news.details', $news->slug) }}" class="hover-red text-dark text-decoration-none">
                                                    {{ $news->title }}
                                                </a>
                                                <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                            </h6>
                                        </div>
                                        {{-- ইমেজ ডানে --}}
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x60/555/fff?text=Sports' }}" 
                                             class="rounded-1 flex-shrink-0" 
                                             width="100" height="60" style="object-fit: cover;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- কলাম ৩: ৫টি লিস্ট আইটেম (Total 5 items: Index 6 to 10) --}}
                <div class="col-lg-4 d-flex flex-column">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        @foreach($sportsNews->slice(6, 5) as $news)
                            <div class="card border-0 border-bottom pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="me-2">
                                        @if($news->subtitle)
                                            <div class="news-subtitle" style="font-size: 11px;">{{ $news->subtitle }}</div>
                                        @endif
                                        <h6 class="fw-bold m-0 hover-red small lh-base">
                                            <a href="{{ route('front.news.details', $news->slug) }}" class="hover-redtext-dark text-decoration-none">
                                                {{ $news->title }}
                                            </a>
                                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </h6>
                                    </div>
                                    <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/100x60/0044cc/fff?text=News' }}" 
                                         class="rounded-1 flex-shrink-0" 
                                         width="100" height="60" style="object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                <div class="col-12 text-center text-muted">
                    <p>কোনো খেলার খবর পাওয়া যায়নি।</p>
                </div>
            @endif

        </div>
    </div>
</section>