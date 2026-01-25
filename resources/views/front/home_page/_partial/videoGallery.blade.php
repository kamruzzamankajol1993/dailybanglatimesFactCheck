<section class="video-gallery-section py-4 bg-light">
    <div class="container">
        
        <div class="d-flex justify-content-between align-items-end mb-3" style="border-bottom: 3px solid #dc3545;">
           
           <a href="{{ route('front.video.gallery') }}" class="text-decoration-none">
            <h5 class="bg-success text-white d-inline-block px-3 py-2 m-0 fw-bold">
                ভিডিও গ্যালারি <i class="fas fa-chevron-right small ms-2"></i>
            </h5>
           </a>
            
            <div class="video-nav-buttons mb-2">
                {{-- Desktop Buttons --}}
                <div class="d-none d-lg-block">
                    <button class="btn btn-outline-dark rounded-circle btn-sm me-1" type="button" data-bs-target="#videoCarouselDesktop" data-bs-slide="prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-outline-dark rounded-circle btn-sm" type="button" data-bs-target="#videoCarouselDesktop" data-bs-slide="next"><i class="fas fa-chevron-right"></i></button>
                </div>
                {{-- Tablet Buttons --}}
                <div class="d-none d-md-block d-lg-none">
                    <button class="btn btn-outline-dark rounded-circle btn-sm me-1" type="button" data-bs-target="#videoCarouselTablet" data-bs-slide="prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-outline-dark rounded-circle btn-sm" type="button" data-bs-target="#videoCarouselTablet" data-bs-slide="next"><i class="fas fa-chevron-right"></i></button>
                </div>
                {{-- Mobile Buttons --}}
                <div class="d-block d-md-none">
                    <button class="btn btn-outline-dark rounded-circle btn-sm me-1" type="button" data-bs-target="#videoCarouselMobile" data-bs-slide="prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn btn-outline-dark rounded-circle btn-sm" type="button" data-bs-target="#videoCarouselMobile" data-bs-slide="next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>

        @if(isset($videoGalleryNews) && count($videoGalleryNews) > 0)
            
            {{-- Desktop Carousel (3 Items) --}}
            <div id="videoCarouselDesktop" class="carousel slide d-none d-lg-block" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($videoGalleryNews->chunk(3) as $key => $chunk)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $video)
                                    <div class="col-4">
                                        {{-- Video Card Code --}}
                                        <div class="card border-0 text-white h-100 video-card-modern">
                                            <div class="position-relative h-100 overflow-hidden rounded">
                                                <img onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : 'https://placehold.co/400x250/333/fff?text=Video' }}" class="card-img h-100 object-fit-cover" alt="{{ $video->title }}">
                                                <div class="video-overlay p-3 d-flex flex-column justify-content-end">
                                                    <h5 class="card-title fw-bold hover-red">
                                                        <a href="{{ route('front.video.details', $video->slug) }}" class="text-white text-decoration-none">{{ Str::limit($video->title, 60) }}</a>
                                                    </h5>
                                                    <a href="{{ route('front.video.details', $video->slug) }}" class="stretched-link play-trigger"><i class="fas fa-play-circle text-danger fs-1 bg-white rounded-circle"></i></a>
                                                    <small class="text-light-50 mt-2"><i class="far fa-clock"></i> {{ bangla_date($video->created_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tablet Carousel (2 Items) --}}
            <div id="videoCarouselTablet" class="carousel slide d-none d-md-block d-lg-none" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($videoGalleryNews->chunk(2) as $key => $chunk)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $video)
                                    <div class="col-6">
                                        {{-- Video Card Code --}}
                                        <div class="card border-0 text-white h-100 video-card-modern">
                                            <div class="position-relative h-100 overflow-hidden rounded">
                                                <img onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : 'https://placehold.co/400x250/333/fff?text=Video' }}" class="card-img h-100 object-fit-cover" alt="{{ $video->title }}">
                                                <div class="video-overlay p-3 d-flex flex-column justify-content-end">
                                                    <h5 class="card-title fw-bold hover-red">
                                                        <a href="{{ route('front.video.details', $video->slug) }}" class="text-white text-decoration-none">{{ Str::limit($video->title, 60) }}</a>
                                                    </h5>
                                                    <a href="{{ route('front.video.details', $video->slug) }}" class="stretched-link play-trigger"><i class="fas fa-play-circle text-danger fs-1 bg-white rounded-circle"></i></a>
                                                    <small class="text-light-50 mt-2"><i class="far fa-clock"></i> {{ bangla_date($video->created_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Mobile Carousel (1 Item) --}}
            <div id="videoCarouselMobile" class="carousel slide d-block d-md-none" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($videoGalleryNews->chunk(1) as $key => $chunk)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $video)
                                    <div class="col-12">
                                        {{-- Video Card Code --}}
                                        <div class="card border-0 text-white h-100 video-card-modern">
                                            <div class="position-relative h-100 overflow-hidden rounded">
                                                <img onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : 'https://placehold.co/400x250/333/fff?text=Video' }}" class="card-img h-100 object-fit-cover" alt="{{ $video->title }}">
                                                <div class="video-overlay p-3 d-flex flex-column justify-content-end">
                                                    <h5 class="card-title fw-bold hover-red">
                                                        <a href="{{ route('front.video.details', $video->slug) }}" class="text-white text-decoration-none">{{ Str::limit($video->title, 60) }}</a>
                                                    </h5>
                                                    <a href="{{ route('front.video.details', $video->slug) }}" class="stretched-link play-trigger"><i class="fas fa-play-circle text-danger fs-1 bg-white rounded-circle"></i></a>
                                                    <small class="text-light-50 mt-2"><i class="far fa-clock"></i> {{ bangla_date($video->created_at) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @else
            <div class="text-center text-muted py-5">
                <p>কোনো ভিডিও পাওয়া যায়নি।</p>
            </div>
        @endif

    </div>
</section>