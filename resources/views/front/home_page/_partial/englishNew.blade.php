<section class="english-section py-4 bg-white border-bottom">
    <div class="container">
        
        <div class="d-flex align-items-end mb-3" style="border-bottom: 3px solid #dc3545;">
            <a href="{{ $front_english_url ?? '#' }}" target="_blank" class="text-white text-decoration-none">
            <div class="bg-success text-white px-3 py-1 fw-bold text-uppercase">English</div>
            </a>
        </div>

        <div class="text-center mb-4">
           <a href="{{ $front_english_url }}" target="_blank"><img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $front_admin_url }}{{ $front_english_banner }}" alt="English Logo" class="img-fluid"></a>
        </div>

        <div class="row g-3">
            @if(isset($englishNews) && count($englishNews) > 0)
                @foreach($englishNews as $news)
                    <div class="col-6 col-md-3">
                        <div class="card border-0 h-100">
                            {{-- ইমেজ --}}
                            <div class="overflow-hidden">
                                <img  onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" src="{{ $news->image ? $front_admin_url.$news->image : 'https://placehold.co/300x180/333/fff?text=News' }}" 
                                     class="card-img-top rounded-0 mb-2 zoom-effect" 
                                     alt="{{ $news->title }}"
                                     style="height: 180px; object-fit: cover;">
                            </div>

                            {{-- টাইটেল ও লিংক --}}
                            <h6 class="fw-bold hover-red small lh-base">
                                <a href="{{ $front_english_url.'news/'.$news->slug }}" class="hover-red text-dark text-decoration-none">
                                    {{ $news->title }}
                                </a>
                            <small class="bangla-date"><i class="far fa-clock me-1"></i>{{ $news->created_at->format('d F Y') }}</small>
                            </h6>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center text-muted">
                    <small>No English news found.</small>
                </div>
            @endif
        </div>
    </div>
</section>