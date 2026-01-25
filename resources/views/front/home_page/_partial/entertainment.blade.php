<section class="entertainment-section py-4 bg-light">
    <div class="container">

        @php
            // ডিফল্ট স্লাগ (যদি ডাটা না থাকে)
            $catSlug = '#'; 
            
            // কন্ট্রোলার থেকে আসা ডাটা থেকে স্লাগ নেওয়া হচ্ছে
            if(isset($entertainmentNews) && count($entertainmentNews) > 0) {
                // প্রথম পোস্টের প্রথম ক্যাটাগরির স্লাগ নেওয়া হলো
                $catSlug = $entertainmentNews->first()->categories->first()->slug ?? '#';
            }
        @endphp
        
        <div class="section-header-wrapper mb-4" style="border-bottom: 3px solid #dc3545;">
            <a href="{{ $catSlug != '#' ? route('front.category.news', $catSlug) : '#' }}" class="text-decoration-none">
            <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 position-relative">বিনোদন</h5>
            </a>
        </div>

        @if(isset($entertainmentNews) && count($entertainmentNews) > 0)
            <div class="row g-3">
                
                {{-- ১. মেইন বড় নিউজ (Left Side - 1 Item) --}}
                <div class="col-lg-5">
                    @php $mainEnt = $entertainmentNews->first(); @endphp
                    @if($mainEnt)
                        <div class="card border-0 h-100 bg-transparent">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $mainEnt->image ? $front_admin_url.$mainEnt->image : 'https://placehold.co/600x380/333/fff?text=Entertainment' }}" 
                                     class="card-img-top rounded-0" 
                                     alt="{{ $mainEnt->title }}">
                                <span class="position-absolute bottom-0 start-0 bg-dark text-white px-2 py-1 m-2 rounded"><i class="fas fa-camera"></i></span>
                            </div>
                            <div class="card-body px-0 pt-3">
                                <h4 class="card-title fw-bold hover-red">
                                    <a href="{{ route('front.news.details', $mainEnt->slug) }}" class="text-dark text-decoration-none hover-red">
                                        {{ $mainEnt->title }}
                                    </a>
                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($mainEnt->created_at) }}</small>
                                </h4>
                                <p class="card-text text-secondary mt-2">
                                    {{ Str::limit(strip_tags($mainEnt->content), 150) }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ২. ডান পাশের গ্রিড (Right Side - 4 Items) --}}
                <div class="col-lg-7">
                    <div class="row g-3">
                        @foreach($entertainmentNews->slice(1, 4) as $news)
                            <div class="col-md-6">
                                <div class="card border-0 text-white overlay-card">
                                    <div class="position-relative">
                                        <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/300x180/444/fff?text=Ent' }}" 
                                             class="card-img rounded-0" 
                                             alt="{{ $news->title }}"
                                             style="height: 180px; object-fit: cover; width: 100%;">
                                        
                                        <div class="card-img-overlay d-flex align-items-end p-0">
                                            <div class="overlay-text p-2 w-100">
                                                <h6 class="fw-bold m-0 lh-base">
                                                    <a href="{{ route('front.news.details', $news->slug) }}" class="text-white text-decoration-none">
                                                        {{ $news->title }}
                                                    </a>
                                                    <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ৩. নিচের সারি (Bottom Row - 4 Items) --}}
            <div class="row g-3 mt-1">
                @foreach($entertainmentNews->slice(5, 4) as $news)
                    <div class="col-lg-3 col-6">
                        <div class="card border-0 text-white overlay-card">
                            <div class="position-relative">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/300x160/222/fff?text=Ent' }}" 
                                     class="card-img rounded-0" 
                                     alt="{{ $news->title }}"
                                     style="height: 160px; object-fit: cover; width: 100%;">
                                
                                <div class="card-img-overlay d-flex align-items-end p-0">
                                    <div class="overlay-text p-2 w-100">
                                        <h6 class="fw-bold m-0 small lh-base">
                                            <a href="{{ route('front.news.details', $news->slug) }}" class="text-white text-decoration-none">
                                                {{ $news->title }}
                                            </a>
                                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ bangla_date($news->created_at) }}</small>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="text-center text-muted py-5">
                <p>কোনো বিনোদন সংবাদ পাওয়া যায়নি।</p>
            </div>
        @endif

    </div>
</section>