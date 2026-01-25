@php
    function convertToBanglaNum($str) {
        $en = ['0','1','2','3','4','5','6','7','8','9', 'am', 'pm'];
        $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯', 'পূর্বাহ্ণ', 'অপরাহ্ণ'];
        return str_replace($en, $bn, $str);
    }
@endphp

<div class="row g-4">
    @forelse($videos as $video)
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 h-100 shadow-sm video-card-modern">
                <div class="position-relative overflow-hidden rounded-top">
                    {{-- থাম্বনেইল --}}
                    <a href="{{ route('front.video.details', $video->slug) }}">
                        <img onerror="this.onerror=null;this.src='{{ $front_admin_url }}{{ $front_logo_name }}';" 
                             src="{{ $video->thumbnail ? $front_admin_url.$video->thumbnail : 'https://placehold.co/400x250/333/fff?text=Video' }}" 
                             class="card-img-top object-fit-cover" 
                             style="height: 200px;"
                             alt="{{ $video->title }}">
                        
                        {{-- প্লে আইকন ওভারলে --}}
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle text-danger bg-white rounded-circle fs-1 shadow"></i>
                        </div>
                    </a>
                </div>

                <div class="card-body p-3">
                    <h6 class="card-title fw-bold lh-base mb-2">
                        <a href="{{ route('front.video.details', $video->slug) }}" class="text-dark text-decoration-none hover-red">
                            {{ Str::limit($video->title, 60) }}
                        </a>
                    </h6>
                    
                    <div class="d-flex justify-content-between text-secondary small mt-2">
                        <span><i class="far fa-clock"></i> {{ convertToBanglaNum($video->created_at->format('d M, Y')) }}</span>
                        <span><i class="far fa-eye"></i> {{ convertToBanglaNum($video->view_count) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">কোনো ভিডিও পাওয়া যায়নি।</h4>
        </div>
    @endforelse
</div>

{{-- প্যাজিনেশন ডিজাইন (লিস্ট পেজ থেকে কপি করা) --}}
@if ($videos->hasPages())
<div class="mt-5 d-flex justify-content-center custom-pagination-wrapper">
    <div class="custom-pagination">
        @if ($videos->onFirstPage())
            <span class="page-link disabled"><i class="fas fa-angle-left"></i></span>
        @else
            <a href="{{ $videos->previousPageUrl() }}" class="page-link" rel="prev"><i class="fas fa-angle-left"></i></a>
        @endif

        @foreach ($videos->getUrlRange(max(1, $videos->currentPage() - 2), min($videos->lastPage(), $videos->currentPage() + 2)) as $page => $url)
            @if ($page == $videos->currentPage())
                <span class="page-link active">{{ convertToBanglaNum($page) }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ convertToBanglaNum($page) }}</a>
            @endif
        @endforeach

        @if ($videos->hasMorePages())
            <a href="{{ $videos->nextPageUrl() }}" class="page-link" rel="next"><i class="fas fa-angle-right"></i></a>
        @else
            <span class="page-link disabled"><i class="fas fa-angle-right"></i></span>
        @endif
    </div>
</div>
@endif